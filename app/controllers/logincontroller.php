<?php


namespace Framework\controllers;

use Framework\Lib\AbstractController;
use Framework\lib\Cipher;
use Framework\lib\FilterInput;
use Framework\lib\Helper;
use Framework\lib\MailModel;
use Framework\lib\Request;
use Framework\Lib\Session;
use Framework\models\UsersModel;
use GuzzleHttp\Client;

class LoginController extends AbstractController
{
    public function DefaultAction()
    {
        // Login action
        if (Request::Check('login')) {
            $username = '';
            if (FilterInput::Email(Request::Post('username', false, true))) {
                $username = Request::Post('username', false, true);
            } elseif (FilterInput::String(Request::Post('username', false, true))) {
                $username = Request::Post('username', false, true);
            }
            $password = Helper::Hash(Request::Post('password', false, true));

            $authenticate = UsersModel::Authenticate($username, $password);
            if ($authenticate) {
                unset($authenticate->password);
                if ($authenticate->twoFA === 1) {
                    Session::Set('loggedin_step_1', $authenticate);
                    $this->logger->info('User Login, First Step', array('Username: ' => $authenticate->username));
                    header("location: " . HOST_NAME . 'login/checkpoint/send');
                } else {
                    Session::Set('loggedin', $authenticate);
                    $this->logger->info('New Login', array('Username: ' => $authenticate->username));
                    header("location: " . HOST_NAME . $authenticate->role);
                }
            } else {
                // check how many attempts
                Session::Set('messages', array('error', 'Login Failed! Username or password is not correct!'));
            }
        }

        // Recover password action.
        if (Request::Check('recover')) {
            $userEmail = FilterInput::Email(Request::Post('email', false, true));
            $user = UsersModel::getAll(" WHERE email = '$userEmail' ", true);
            if ($user) {
                $this->logger->info('Reset Password Requested', array('username' => $user->username));

                $resetMessage = $this->GenerateRecoverEmail($user);
                var_dump($resetMessage);


                if ($resetMessage) {
                    try {
                        $mail = new MailModel();
                        $mail->from_email = CONTACT_EMAIL;
                        $mail->from_name = CONTACT_NAME;
                        $mail->to_email = $user->email;
                        $mail->to_name = $user->firstName . ' ' . $user->lastName;
                        $mail->subject = 'Reset Password Request';
                        $mail->message = $resetMessage;
                        $mail->alt_message = $resetMessage;

                        if ($mail->Send()) {
                            Session::Set('messages', array('success', "An email with the reset link was sent to you, Don't forget to check your spam if you didn't find the email in your inbox!"));
                            $this->logger->info('Reset Password Email Sent', array('username' => $user->username));
                        } else {
                            throw new \Exception();
                        }
                    } catch (\Exception $e) {
                        Session::Set('messages', array('error', "Sorry, We couldn't send an email with the reset link at the moment! Please try again later or contact support."));
                        $this->logger->error("Failed to send reset password email", array('username' => $user->username, 'error: ' => $e->getMessage()));
                    }
                } else {
                    Session::Set('messages', array('error', "Sorry, We couldn't send an email with the reset link at the moment! Please try again later or contact support."));
                    $this->logger->error("Failed to send reset password email", array('username' => $user->username, 'error: ' => 'Couldn\'t generate reset email!'));
                }
            } else {
                Session::Set('messages', array('error', "Sorry, We couldn't find any user associated with your email address"));
            }
        }

        $this->_template->SetViews(['login-header', 'view'])->Render();
    }

    public function CheckpointAction()
    {
        if (Session::Exists('loggedin_step_1')) {
            $authenticate = Session::Get('loggedin_step_1');

            $code_attempts_stop = true;
            if (Session::Exists('attempts')) {
                if (Session::Get('attempts')['count'] >= 10 && Session::Get('attempts')['time'] >= SERVER_TIMESTAMP) {
                    Session::Set('messages', array('error', 'Error, too many attempts with wrong code! Please try again after one hour or contact support.'));
                    $code_attempts_stop = false;
                }
            }

            if ($code_attempts_stop) {
                $param = ($this->_params) != null ? $this->_params[0] : false;
                if ($param !== false && ($param == 'send' || $param == 'resend')) {
                    $old_user = UsersModel::getOne($authenticate->id);
                    $attempts = true;
                    if ($old_user->loginAttempts) {
                        if (($old_user->loginAttempts_time >= SERVER_TIMESTAMP) && $old_user->loginAttempts > 6) {
                            Session::Set('messages', array('error', 'Error sending login code. too many attempts! Please try again after one hour or contact support.'));
                            $attempts = false;
                        }
                    }

                    if ($attempts) {
                        if ($authenticate->phone) {
                            $auth_code = $this->SendAuthCode($authenticate->phone);
                            if ($auth_code) {
                                $user = new UsersModel();
                                $user->id = $authenticate->id;
                                $user->authCode = $auth_code;
                                $user->authCode_time = SERVER_TIMESTAMP;

                                if ($old_user->loginAttempts_time >= SERVER_TIMESTAMP) {
                                    $user->loginAttempts = intval($old_user->loginAttempts) + 1;
                                } else {
                                    $user->loginAttempts = 1;
                                    $user->loginAttempts_time = SERVER_TIMESTAMP + 3600;
                                }

                                if (!$user->Save()) {
                                    Session::Set('messages', array('error', 'Failed to send login code, please try again later or contact support!'));
                                }
                            } else {
                                Session::Set('messages', array('error', 'Failed to send login code, please try again later or contact support!'));
                            }
                        }
                    }

                    ?>
                    <script>window.history.pushState({}, null, location.origin+"/login/checkpoint")</script>
                    <?php
                }

                if (Request::Check('submit')) {
                    $user_data = UsersModel::getOne($authenticate->id);
                    if (Request::Check('code') && $user_data->authCode) {
                        $user_code = FilterInput::Int(Request::Post('code', false, true));
                        if ($user_code && $user_data->authCode) {
                            if ((SERVER_TIMESTAMP - $user_data->authCode_time) < 3600) {
                                if ($user_code == $user_data->authCode) {
                                    Session::Set('loggedin', $authenticate);
                                    $this->logger->info('Admin Login', array('Username: ' => $authenticate->username));
                                    if ($authenticate->role == 'admin') {
                                        header("location: " . HOST_NAME . 'admin');
                                    } elseif ($authenticate->role == 'collaborator') {
                                        header("location: " . HOST_NAME . 'collaborator');
                                    }
                                } else {
                                    if (Session::Exists('attempts')) {
                                        if (Session::Get('attempts')['time'] >= SERVER_TIMESTAMP) {
                                            $code_attempts = Session::Get('attempts')['count'] + 1;
                                            $code_attempts_time = Session::Get('attempts')['time'];
                                        } else {
                                            $code_attempts = 1;
                                            $code_attempts_time = SERVER_TIMESTAMP + 3600;
                                        }
                                    } else {
                                        $code_attempts = 1;
                                        $code_attempts_time = SERVER_TIMESTAMP + 3600;
                                    }

                                    $code_attempts_session = array('count' => $code_attempts, 'time' => $code_attempts_time);
                                    Session::Set('attempts', $code_attempts_session);

                                    Session::Set('messages', array('error', 'The code you entered doesn\'t match the code sent to you!'));
                                }
                            } else {
                                Session::Set('messages', array('error', 'The code you entered has expired, Please request a new one.'));
                            }
                        }
                    }
                }
            }

            $this->_template->SetViews(['login-header', 'view'])->Render();
        }
    }

    public function ResetAction()
    {
        if (Session::Exists('resetUserId')) {
            $userObj = UsersModel::getOne(Session::Get('resetUserId'));
            $userToken = ($this->_params) ? $this->_params[0] : false;
            if ($userObj && $userToken) {
                if ($userObj->forgotPasswordToken == $userToken) {
                    if ($userObj->forgotPasswordToken_time >= SERVER_TIMESTAMP) {
                        if (Request::Check('submit')) {
                            $password = FilterInput::String(Request::Post('password', false, false));
                            $cPassword = FilterInput::String(Request::Post('password-confirm', false, false));

                            if ($password == $cPassword) {
                                $user = new UsersModel();
                                $user->id = $userObj->id;
                                $user->password = Helper::Hash($password);
                                $user->lastUpdate = date('Y-m-d h:i:s');

                                if ($user->Save()) {
                                    $userTokenR = new UsersModel();
                                    $userTokenR->id = $userObj->id;
                                    $userTokenR->forgotPasswordToken = 'token used!';
                                    $userTokenR->Save();

                                    $this->logger->info("Password was recovered successfully", array('username' => $userObj->username));
                                    Session::Set('messages', array('success', "You new password was saved successfully."));
                                    header('location: ' . HOST_NAME . 'login');
                                } else {
                                    $this->logger->error("Error recovering password, Unknown saving issue.", array('username' => $userObj->username));
                                    Session::Set('messages', array('error', "Couldn't save your new password, some unknown error happened!"));
                                }
                            } else {
                                Session::Set('messages', array('error', 'Your new password and password confirmation don\'t match!'));
                            }
                        }

                        $this->_template->SetViews(['login-header', 'view'])->Render();
                    } else {
                        Session::Set('messages', array('error', 'Your reset link expired, Please request new one.'));
                        header('location: ' . HOST_NAME . 'login');
                    }
                } else {
                    Session::Set('messages', array('error', 'Reset link was invalid, Please start over!'));
                    header('location: ' . HOST_NAME . 'login');
                }
            } else {
                Session::Set('messages', array('error', 'Something went wrong, Please start over!'));
                header('location: ' . HOST_NAME . 'login');
            }
        }
    }



    private function GenerateRecoverEmail($userObj)
    {
        if ($userObj) {
            $resetToken = substr(md5(mt_rand()), 0, 32);
            $resetURL = HOST_NAME . 'login/reset/' . $resetToken;
            $message = "You have requested to reset your password.
					<br>
					If you didn't request to reset your password ignore this email.
					<br>
					If you want to create a new password now please click 
					<a href=" . $resetURL . ">Here</a>
					or copy and paste this URL to your browser: <br>" . $resetURL ."
					<br><br>
					This link expires in 24 hours, after that you would have to request new link.
					<br><br>";

            $user = new UsersModel();
            $user->id = $userObj->id;
            $user->forgotPasswordToken = $resetToken;
            $user->forgotPasswordToken_time = SERVER_TIMESTAMP + 86400;
            if ($user->Save()) {
                Session::Set('resetUserId', $userObj->id);
                return $message;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    private function SendAuthCode($phone)
    {
        if ($phone) {
            $code = substr(number_format(time() * rand(),0,'',''),0,6);
var_dump($code);
return $code;
die();
            $client = new Client();
            $currentTime = new \DateTime(null, new \DateTimeZone("UTC"));
            $sendRequestBody = json_decode('{ "messages" : [ { "content" : "Your Login Code for Password Vault is: '.$code.' ", "destination" : "'.$phone.'" } ] }',true);

            $sendResponse = $client->request('POST', "https://rest.mymobileapi.com/v1/bulkmessages", [
                'json' => $sendRequestBody,
                'headers' => ['Authorization' => "Bearer " . MOBILE_API_KEY],
                'http_errors' => false
            ]);

            if($sendResponse->getStatusCode() == 200){
                $this->logger->info('SMS sent successfully for user login', array('Username: ' => Session::Get('loggedin_step_1')->username, "Details: " => $sendResponse->getBody()));
                return $code;
            } else {
                $this->logger->error('Failed to send sms for user login', array('Username: ' => Session::Get('loggedin_step_1')->username, "Error details: " => $sendResponse->getBody()));
                return false;
            }
        }
    }
}