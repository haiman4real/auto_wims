pipeline {
    agent any

    environment {
        // Default deployment directory (for develop/feature branches)
        DEFAULT_DEPLOY_DIR = "/home/autowims-demo/htdocs/demo.autowims.com.ng"
        // Deployment directory for release branch
        RELEASE_DEPLOY_DIR = "/home/autowims-v2/htdocs/v2.autowims.com.ng"
        // Deployment directory for master branch
        MASTER_DEPLOY_DIR = "/home/gopodlab-erp/htdocs/erp.gopodlab.com"
    }

    stages {
        stage('Log Target Branch') {
            steps {
                echo "Target branch is: ${env.BRANCH_NAME}"
            }
        }

        stage('Checkout and Deploy') {
    when {
        expression {
            return env.BRANCH_NAME == 'uat' ||
                   env.BRANCH_NAME == 'main'
        }
    }
    steps {
        script {
            // Choose deployment directory based on the branch
            def deployDir = env.DEFAULT_DEPLOY_DIR
            if (env.BRANCH_NAME == 'main') {
                deployDir = env.MASTER_DEPLOY_DIR
            }
            echo "Deploy directory is set to: ${deployDir}"

            // Extract user and group correctly from the deploy directory path
            def tokens = deployDir.tokenize('/')
            def userGroup = tokens[1]
            echo "Extracted user and group: ${userGroup}"

            // Ensure the deploy directory is a safe directory for git
            def isSafeDirectoryConfigured = sh(
                script: "git config --global --get-all safe.directory | grep -Fxq '${deployDir}' && echo 'true' || echo 'false'",
                returnStdout: true
            ).trim()

            if (isSafeDirectoryConfigured != 'true') {
                echo "Adding ${deployDir} to git safe.directory"
                sh "git config --global --add safe.directory ${deployDir}"
            } else {
                echo "${deployDir} is already configured as a safe directory"
            }

            // Execute deployment steps in the selected directory
            sh """
                echo "Changing to deployment directory: ${deployDir}"
                cd ${deployDir} || exit 1

                echo "Pulling latest changes from origin/${BRANCH_NAME}"
                git fetch
                git stash
                git switch ${BRANCH_NAME}
                git pull --rebase=false origin ${BRANCH_NAME}

                echo "Running additional commands..."
                php artisan optimize
                npm install
                npm run build

                echo "Changing ownership of files to: ${userGroup}:${userGroup}"

            """
        }
    }
}
    }

    post {
        failure {
            echo "Deployment failed. Please check the logs."
        }
    }
}