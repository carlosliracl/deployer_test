<?php

require 'vendor/autoload.php';
require 'recipe/common.php';


localServer('testing')
    ->user('vagrant')
    ->stage('testing')
    ->env('deploy_path', '/vagrant_data/www/deployed');

server('production', 'staging.example.com', '22')
    ->user('username')
    ->password('password')
    ->stage('production')
    ->env('deploy_path', '/path/to/remote/deployment/directory');

set('repository', 'https://github.com/carlosliracl/mtproto.git');

task('deploy', [
    'deploy:prepare',
    'deploy:release',
    'deploy:update_code',
    'deploy:vendors',
    'deploy:symlink',
    'cleanup'
])->desc("Main deployment process");


task('deploy:done', function () {
    write('Deploy done!');
})->desc("When deployment's completed");

task('reload:php-fpm', function () {
    run('sudo /usr/sbin/service php5-fpm reload');
})->desc('Reload PHP5 FPM');

after('deploy', 'reload:php-fpm');
after('deploy', 'deploy:done');

?>
