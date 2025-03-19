import os
import requests
import json
import time

# Configuration
API_TOKEN = None  # Sera généré automatiquement
USERNAME = "parrainage-app"
DOMAIN = f"{USERNAME}.pythonanywhere.com"
API_BASE = "https://www.pythonanywhere.com/api/v0/user/{username}/".format(username=USERNAME)

def create_account():
    response = requests.post(
        "https://www.pythonanywhere.com/api/v0/user/",
        json={
            "username": USERNAME,
            "email": f"{USERNAME}@temp.com",
            "password": "TempPass123!"
        }
    )
    return response.json()["token"]

def setup_webapp():
    headers = {"Authorization": f"Token {API_TOKEN}"}
    
    # Créer l'application web
    response = requests.post(
        API_BASE + "webapps/",
        headers=headers,
        json={
            "domain_name": DOMAIN,
            "python_version": "3.9",
            "source_directory": "/home/{}/parrainage-app".format(USERNAME),
            "virtualenv_path": "/home/{}/venv".format(USERNAME),
            "working_directory": "/home/{}/parrainage-app".format(USERNAME)
        }
    )
    
    # Configuration de la base de données MySQL
    response = requests.post(
        API_BASE + "mysql/",
        headers=headers,
        json={
            "name": "parrainage_db"
        }
    )

def deploy_code():
    headers = {"Authorization": f"Token {API_TOKEN}"}
    
    # Clone du repo
    os.system(f"git clone https://github.com/SeydinaAlioune/parrainage-app.git /home/{USERNAME}/parrainage-app")
    
    # Installation des dépendances
    os.system(f"cd /home/{USERNAME}/parrainage-app && composer install")
    os.system(f"cd /home/{USERNAME}/parrainage-app && npm install && npm run build")
    
    # Configuration Laravel
    os.system(f"cd /home/{USERNAME}/parrainage-app && php artisan key:generate")
    os.system(f"cd /home/{USERNAME}/parrainage-app && php artisan migrate --force")

def main():
    global API_TOKEN
    API_TOKEN = create_account()
    setup_webapp()
    deploy_code()
    print(f"Application déployée avec succès sur https://{DOMAIN}")
    print(f"Identifiants :")
    print(f"Username : {USERNAME}")
    print(f"Password : TempPass123!")

if __name__ == "__main__":
    main()
