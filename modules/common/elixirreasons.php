<?php 
switch($device->status)
{
    case 'A':
        echo 'Normal Speed Periodic';
        break;
    case 'B':
        echo 'Excess Speed Periodic';
        break;
    case 'C':
        echo 'Back To Normal Speed';
        break;
    case 'D':
        echo 'Excess Speed Start';
        break;
    case 'E':
        echo 'Stop Mode';
        break;
    case 'F':
        echo 'Motion Start';
        break;
    case 'G':
        echo 'Motion Stop';
        break;
    case 'H':
        echo 'GPS Fixed';
        break;
    case 'I':
        echo 'Initialize';
        break;
    case 'J':
        echo 'Ignition Status Change';
        break;
    case 'K':
        echo 'Box Open';
        break;
    case 'L':
        echo 'Transit In External Power';
        break;
    case 'M':
        echo 'Dont Know';
        break;
    case 'N':
        echo 'SOS Pressed';
        break;
    case 'O':
        echo 'Change in Digital Input State';
        break;
    case 'P':
        echo 'Response To Position Command';
        break;
    case 'Q':
        echo 'Response To Parameter Change Command';
        break;
    case 'R':
        echo 'Response To Parameter Query';
        break;
    case 'S':
        echo 'Harsh Break';
        break;
    case 'T':
        echo 'Immobilzed';
        break;
    case 'U':
        echo 'GPS Failed';
        break;
    case 'W':
        echo 'Sim Card Removed';
        break;
    case 'Z':
        echo 'Error';
        break;
    default :
        echo $device->status;
}

?>