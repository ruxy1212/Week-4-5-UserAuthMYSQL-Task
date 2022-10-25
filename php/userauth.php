<?php

require_once "../config.php";

//register users
function registerUser($fullnames, $email, $password, $gender, $country){
    //create a connection variable using the db function in config.php
    $conn = db();

    $sql_check= "SELECT * FROM Students WHERE email='$email'";
    $result = mysqli_query($conn, $sql_check);
    
   //check if user with this email already exist in the database
   if(mysqli_num_rows($result) > 0){
        echo "<script type='text/javascript'>
                alert('User already exists!');
                window.location = '../forms/register.html';
            </script>";
   }else{
        $sql_insert = "INSERT INTO Students (full_names, email, password, gender, country) VALUES ('$fullnames', '$email', '$password', '$gender', '$country')";
        $result = mysqli_query($conn, $sql_insert);
        if($result){
            echo "<script type='text/javascript'>
                    alert('User Successfully registered!');
                    window.location = '../forms/login.html';
                </script>";
        }else{
            echo "<script type='text/javascript'>
                    alert('Something went wrong, try again!');
                    window.location = '../forms/register.html';
                </script>";
        }
   }
}


//login users
function loginUser($email, $password){
    //create a connection variable using the db function in config.php
    $conn = db();
    //open connection to the database and check if username exist in the database
    $sql_check= "SELECT * FROM Students WHERE email='$email'";
    $result = mysqli_query($conn, $sql_check);
    //if it does, check if the password is the same with what is given
    if(mysqli_num_rows($result)>0){
        $row = mysqli_fetch_assoc($result);
        $user_email = $row['email'];
        $user_pass = $row['password'];
        $user_name = $row['full_names'];
        //if true then set user session for the user and redirect to the dasbboard
        if($user_pass == $password && $user_email == $email){
            session_start();
            $_SESSION['username'] = $user_name;
            echo "<script type='text/javascript'>
                    alert('Login Successful!');
                    window.location = '../dashboard.php';
                </script>";
        } else {
            echo "<script type='text/javascript'>
                    alert('Incorrect password!');
                    window.location = '../forms/login.html';
                </script>";
        }
    }else{
        //account doesn't exist
        echo "<script type='text/javascript'>
                alert('Email not registered!');
                window.location = '../forms/login.html';
            </script>";
    }
    
}


function resetPassword($email, $password){
    //create a connection variable using the db function in config.php
    $conn = db();
    //open connection to the database and check if username exist in the database
    $sql_check= "SELECT * FROM Students WHERE email='$email'";
    $result = mysqli_query($conn, $sql_check);
    if(mysqli_num_rows($result)>0){
        //if it does, replace the password with $password given
        $sql_update = mysqli_query($conn, "UPDATE Students SET password='$password' WHERE email='$email'");
        if($sql_update){
            echo "<script type='text/javascript'>
                    alert('Password was changed successfully!');
                    window.location = '../forms/login.html';
                </script>";
        } else {
            echo "<script type='text/javascript'>
                    alert('User does not exist');
                    window.location = '../forms/resetpassword.html';
                </script>";
        }
    }else {
        //account doesn't exist
        echo "<script type='text/javascript'>
                alert('Email not registered!');
                window.location = '../forms/resetpassword.html';
            </script>";
    } 
}

function getusers(){
    $conn = db();
    $sql = "SELECT * FROM Students";
    $result = mysqli_query($conn, $sql);
    echo"<html>
    <head></head>
    <body>
    <a href='../dashboard.php' style='background: blue;padding: 6px 15px;border-radius: 6px;color: white;text-decoration: none;'> Go Back To Dashboard </a>
    <center><h1><u> ZURI PHP STUDENTS </u> </h1> 
    <table border='1' style='width: 700px; background-color: magenta; border-style: none'; >
    <tr style='height: 40px'><th>ID</th><th>Full Names</th> <th>Email</th> <th>Gender</th> <th>Country</th> <th>Action</th></tr>";
    if(mysqli_num_rows($result) > 0){
        while($data = mysqli_fetch_assoc($result)){
            //show data
            echo "<tr style='height: 30px'>".
                    "<td style='width: 50px; background: blue'>" . $data['id'] . "</td>
                    <td style='width: 150px'>" . $data['full_names'] .
                    "</td> <td style='width: 150px'>" . $data['email'] .
                    "</td> <td style='width: 150px'>" . $data['gender'] . 
                    "</td> <td style='width: 150px'>" . $data['country'] . 
                    "</td>
                    <td style='width: 150px; text-align: center;'> 
                        <form action='action.php' method='post'>
                            <input type='hidden' name='id' value='" . $data['id'] . "'>
                            <button type='submit', name='delete' style='background: blue; color: white; cursor: pointer;'> DELETE </button>
                        </form>
                    </td>
                </tr>";
        }
        echo "</table></table></center></body></html>";
    }
    //return users from the database
    //loop through the users and display them on a table
}

 function deleteaccount($id){
     $conn = db();
     //delete user with the given id from the database
     echo "<script type='text/javascript'>
            var response = confirm('Are you sure you want to delete this account? Once the account is deleted, all of its resources and data will be permanently erased.')
            if(response == true){           
                window.location = 'action.php?delete=Yess&id=$id';            
            }
            else{     
            window.location = 'action.php?all';    
            }
        </script>";
 }

 function deleteconfirm($id){
     $conn = db();
     $sql_delete = "DELETE FROM Students WHERE id='$id'";
     $result = mysqli_query($conn, $sql_delete);
     if($result){
        echo "<script type='text/javascript'>
                alert('User Deleted successfully!');
                window.location = '../dashboard.php';
            </script>";
    } else {
        echo "<script type='text/javascript'>
                alert('There was an error! Try again');
                window.location = 'action.php?all';
            </script>";
    }
 }

 function logout(){
    if(session_status() != PHP_SESSION_NONE){
        session_unset();
    }
    session_destroy();
 }
