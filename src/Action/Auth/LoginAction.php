<?php

namespace App\Action\Auth;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class LoginAction
{
    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response
    ): ResponseInterface {
//        $html = '<form action="login" method="POST">
//        Username: <input type="text" name="username"><br>
//        Password: <input type="password" name="password"><br>
//        <input type="submit" value="Login">
//        </form>';

        $html = '<h2>Login Page</h2><br>
    <div class="login">
    <form  action="login" method="POST" >
        <label><b>User Name
        </b>
        </label>
        <input type="text" name="username"  placeholder="Username">
        <br><br>
        <label><b>Password
        </b>
        </label>
        <input type="password" name="password"  placeholder="Password">
        <br><br>
        <input type="submit"  value="Log In Here">
        <br><br>
     
    </form>';
        $response->getBody()->write($html);

        return $response;
    }
}