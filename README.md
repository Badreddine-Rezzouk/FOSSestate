# FOSS Estate

An open source project to bring real estate management to everyone without breaking the bank.

# The concept

FOSS Estate is a star architecture solution with a self-hosted backend, a public website, a Java desktop client, and a mobile Android client.

## The structure

- Backend server and API: `/php`
- Website marketing and dashboard: `/www`
- Desktop Java application: `/src`
- Android mobile application: `/app`

## The technologies

- Server: PHP, MySQL
- Website: React, Tailwind CSS
- Web App: Java Swing
- Mobile App: Kotlin (Android)

## Full system setup

### 1) Backend server and database

Use Docker to run the API server and MySQL database quickly:

```bash
cd c:\Users\<user>\FOSSestate
docker composeg up --build
```

Then open:

- API server: `http://localhost:8080`
- API health: `http://localhost:8080/api/dashboard`

The database uses the schema in `php/database_setup.sql` and seeds default demo data.

Default admin credentials:

- username: `admin`
- password: `admin123`

### 2) Website

Install dependencies and start the React website:

```bash
cd www
npm install
npm start
```

The website will open at `http://localhost:3000` and proxy API calls to `http://localhost:8080`.

### 3) Java desktop app

Compile and run the starter Java application from the `src` folder:

```bash
cd src
javac Application.java
java Application
```

This desktop application is a starter skeleton for the FOSS Estate desktop client.

### 4) Android mobile app

Open the `app` folder in Android Studio or run with Gradle:

```bash
cd app
./gradlew assembleDebug
```

The Android project includes a Compose-based starter application and can be extended with login, tenant management, and rental workflows.

## Folder summary

- `/php`: PHP API, database config, Docker setup, and feature toggles
- `/www`: React + Tailwind website
- `/src`: Java Swing web app starter
- `/app`: Kotlin Android mobile app

## Notes

- The backend is currently implemented with a lightweight PHP API and MySQL.
- The React website uses a development proxy to talk to the backend.
- The Java and Android clients are starter applications ready for feature expansion.
