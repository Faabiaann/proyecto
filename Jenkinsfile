pipeline {
    agent any

    environment {
        IMAGE = "www"
        SSH_USER = 'ec2-user' // Reemplaza con el nombre de usuario correcto
        SSH_HOST = 'ec2-54-234-95-127.compute-1.amazonaws.com' // Reemplaza con la IP o el hostname de la instancia de aplicación
    }

    stages {
        stage('Clonar repositorio') {
            steps {
                git branch: 'master', credentialsId: 'github-creds', url: 'https://github.com/Faabiaann/proyecto.git'
            }
        }

        stage('Construir imagen') {
            steps {
                script {
                    // Construye la imagen
                    docker.build(IMAGE, '-f Dockerfile')
                }
            }
        }

        stage('Actualizar codigo') {
            steps {
                sshagent(['d2f1dcc3-ad30-47b4-9a4d-8b9c71fda2d9']) {
                    sh """
                    ssh -o StrictHostKeyChecking=no ${SSH_USER}@${SSH_HOST} << EOF
                    cd proyecto/proyecto
                    git fetch
                    git pull
EOF
                    """
                }
            }
        }

        stage('Build') {
            steps {
                sshagent(['d2f1dcc3-ad30-47b4-9a4d-8b9c71fda2d9']) {
                    sh """
                    ssh -o StrictHostKeyChecking=no ${SSH_USER}@${SSH_HOST} << EOF
                    cd proyecto/proyecto
                    docker-compose down
                    docker-compose build
EOF
                    """
                }
            }
        }

        stage('Deploy') {
            steps {
                sshagent(['d2f1dcc3-ad30-47b4-9a4d-8b9c71fda2d9']) {
                    sh """
                    ssh -o StrictHostKeyChecking=no ${SSH_USER}@${SSH_HOST} << EOF
                    cd proyecto/proyecto
                    docker-compose up -d
EOF
                    """
                }
            }
        }
    }
}

