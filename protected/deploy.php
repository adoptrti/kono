<?php

namespace Deployer;

require 'recipe/yii.php';

// Project name
set ( 'application', 'Kono' );

// Project repository
set ( 'repository', 'https://github.com/thevikas/kono.git' );

// [Optional] Allocate tty for git clone. Default value is false.
set ( 'git_tty', true );

// Shared files/dirs between deploys
add ( 'shared_files', [ ] );
add ( 'shared_dirs', [ ] );

// Writable dirs by web server
add ( 'writable_dirs', [ ] );

// Hosts

// host('redchhaya')
// ->set('deploy_path', '~/projects/konoweb');

host ( 'dream' )->set ( 'deploy_path', '~/kono.adoptrti.org' );

// Tasks

task ( 'build', function ()
{
    run ( 'cd {{release_path}} && build' );
} );

task ( 'ls', function ()
{
    $result = runLocally('ls -l');
    writeln($result);
} );

task ( 'dbscan', function ()
{
    $result = runLocally("~/kono.adoptrti.org/protected/yiic tools dbscan");
    writeln($result);
} );


task ( 'importpatch', function ()
{
    $db = require_once 'config/database.php';
    $mats = [];
    if(!preg_match("/host=(?<dbhost>[^;]+);dbname=(?<dbname>\w+)/",$db['connectionString'],$mats))
        die("dbname/host not found in !");
    
    $dbname = $mats['dbname'];
    $dbhost = $mats['dbhost'];
    
    $result = runLocally('bzcat ~/kono.adoptrti.org/protected/data/patch.sql.bz2|' .
                         "mysql -h $dbhost -u {$db['username']} -p{$db['password']} $dbname");
    writeln($result);
} );

task ( 'patch', function ()
{
    upload('patch.sql.bz2', '~/kono.adoptrti.org/protected/data');
    $result = run("cd ~/kono.adoptrti.org/protected;/usr/local/php71/bin/php vendor/bin/dep importpatch");
    $result = run("cd ~/kono.adoptrti.org/protected;/usr/local/php71/bin/php vendor/bin/dep dbscan");
    writeln($result);
} );

task ( 'checkout', function ()
{
    $result = run("cd ~/kono.adoptrti.org/protected;git pull");
    writeln($result);
} );

// [Optional] if deploy fails automatically unlock.
after ( 'deploy:failed', 'deploy:unlock' );

