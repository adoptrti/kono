<?php
namespace Deployer;

require 'recipe/yii.php';

// Project name
set('application', 'Kono');

// Project repository
set('repository', 'https://github.com/thevikas/kono.git');

// [Optional] Allocate tty for git clone. Default value is false.
set('git_tty', true); 

// Shared files/dirs between deploys 
add('shared_files', []);
add('shared_dirs', []);

// Writable dirs by web server 
add('writable_dirs', []);


// Hosts

host('redchhaya')
    ->set('deploy_path', '~/projects/konoweb');    
    
// Tasks

task('build', function () {
    run('cd {{release_path}} && build');
});

// [Optional] if deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');

