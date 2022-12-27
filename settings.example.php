<?php


// You can set the users here. Put the username in array key, in in each you can set password and permissions
// there are 4 permissions: PERMISSION_READ_LOG, PERMISSION_READ_STATS, PERMISSION_START, PERMISSION_STOP
// You can set permissions of the user to each command like this:
// You can put them in an array like this:
// 'permissions_for_commands' => [
//    'Main' => [PERMISSION_READ_LOG, PERMISSION_READ_STATS],
// ],
// Also if you want to give someone all of the permissions, you don't need to write them all.
// You can write it this way: 'Main' => PERMISSION_ALL
const USERS = [
    /*'admin' => [
        'password' => 'admin',
        'permissions_for_commands' => [
            'Main' => PERMISSION_ALL,
            'Second' => [
                PERMISSION_READ_LOG,
            ],
        ],
    ],*/
];


// The user logs will be saved into this file
// The logs include datetime, username and the action happened
// For example it says user Admin has started the process at datetime X
const USER_LOGS_FILE = 'user-logs.txt';


// You can implement multiple commands here
// User can select which command to manage in a dropdown in the main page of the app
const COMMANDS = [
    'Main' => [
        'command' => 'python3 -u test-script.py', // the command to run
        'working_dir' => __DIR__, // working directory of the command
        'log_file' => __DIR__ . '/log-file.txt', // a file to log command output to it
        'log_tail_maximum_lines' => 20, // number of the lines for the log file tail when we show the logs
        'process_id_file' => 'process-id.txt', // a file to store the process id for the command
        'kill_signal' => S_TERM, // the signal you want to be sent to the command when the stop button gets pressed
        'description' => 'This is a description for the first command', // a description for the command
        'custom_actions' => ['force_kill'],
    ],
    'Second' => [
        'command' => 'python3 -u test-script-2.py',
        'working_dir' => __DIR__,
        'log_file' => __DIR__ . '/log-file-2.txt',
        'log_tail_maximum_lines' => 20,
        'process_id_file' => 'process-id-2.txt',
        'kill_signal' => S_KILL, // these options are available: S_HUP, S_INT, S_QUIT, S_ILL, S_TRAP, S_IOT, S_BUS, S_FPE, S_KILL, S_USR1, S_SEGV, S_USR2, S_PIPE, S_ALRM, S_TERM, S_STKFLT, S_CHLD, S_CONT, S_STOP, S_TSTP, S_TTIN, S_TTOU, S_URG, S_XCPU, S_XFSZ, S_VTALRM, S_PROF, S_WINCH, S_POLL, S_PWR, S_SYS
        'description' => 'This is a description for the second command. You can leave this field as a blank "" string',
        'custom_actions' => [],
    ],
];


// You can define custom actions in addition to "start" and "stop"
// Then you can assign these to different commands
$GLOBALS['CUSTOM_ACTIONS'] = [
    'force_kill' => [
        'title' => 'Force kill',
        'description' => 'Kills the process forcefully',
        'button_color' => 'yellow',
        'is_enabled' => (function ($processID) {
            if (!user_has_permission(123)) {
                return false; // user doesn't have permission for this action, so disable it
            }

            return $processID !== false; // only enable if process is running
        }),
        'handle' => (function ($processID) {
            exec('kill -KILL ' . $processID);
        }),
    ],
];
