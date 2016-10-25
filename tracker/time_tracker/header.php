<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Time_Tracker</title>
        <style>
            table{
                width: 50%;
                margin-left:400px;
                table-layout: fixed;
            }
            table, th, td {
                border: 1px solid #85adad;
                border-collapse: collapse;
                text-align: center;
            }
            th{
                padding:10px;
            }
            td{
                padding: 5px;
                word-wrap: break-word;
            }
            tbody tr:hover{
                background-color: #f2f2f2;
            }
        </style>
    </head>
    <body>
        <?php session_start(); ?>