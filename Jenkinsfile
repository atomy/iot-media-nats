pipeline {
    agent {
        label 'default'
    }

    environment {
        APP_NAME = 'iot-media-api'
    }

    stages {
        stage('Build') {
            steps {
                withCredentials([string(credentialsId: 'iot-media-api-deploy-host', variable: 'DEPLOY_HOST'),
                    string(credentialsId: 'iot-media-api-deploy-login', variable: 'DEPLOY_LOGIN'),
                    string(credentialsId: 'iot-media-api-ecr-prefix', variable: 'ECR_PREFIX'),
                    string(credentialsId: 'iot-media-api-nats-host', variable: 'NATS_HOST'),
                    file(credentialsId: 'iot-media-api-ssh-priv', variable: 'SSH_PRIV'),
                    file(credentialsId: 'iot-media-api-ssh-pub', variable: 'SSH_PUB')]) {
                        echo 'Configuring...'
                        sh './scripts/configure.sh'
                        echo 'Configuring...DONE'
                }

                sshagent (credentials: ['github-iogames-jenkins']) {
                    echo 'Auto-tagging...'
                    sh './scripts/auto-tag.sh'
                    echo 'Auto-tagging...DONE'
                    //sh 'ssh -o StrictHostKeyChecking=no -l cloudbees 192.168.1.106 uname -a'
                }

                echo 'Building...'
                sh './scripts/build.sh'
                echo 'Building...DONE'
            }
        }

        stage('Push ECR') {
            steps {
                withCredentials([[$class: 'AmazonWebServicesCredentialsBinding', accessKeyVariable: 'AWS_ACCESS_KEY_ID', credentialsId: 'aws-ecr', secretKeyVariable: 'AWS_SECRET_ACCESS_KEY']]) {
                    sh "aws configure set aws_access_key_id ${AWS_ACCESS_KEY_ID}"
                    sh "aws configure set aws_secret_access_key ${AWS_SECRET_ACCESS_KEY}"
                    sh '$(aws ecr get-login --no-include-email --region eu-central-1)'

                    echo 'Pushing ECR...'
                    sh './scripts/push.sh'
                    echo 'Pushing ECR...DONE'
                }
            }
        }

        stage('Deploy') {
            steps {
                echo 'Deploying....'
                sshagent(credentials : ['deploy-key-docker02']) {
                    sh './scripts/deploy.sh'
                }
                echo 'Deploying....DONE'

                withCredentials([string(credentialsId: 'discord-webhook-release-url', variable: 'DISCORD_WEBHOOK_URL')]) {
                        echo 'Sending release-notification...'
                        sh './scripts/notification.sh'
                        echo 'Sending release-notification...DONE'
                }
            }
        }
    }
}
