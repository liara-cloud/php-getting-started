# S3-Compatible Object Storage Management in PHP

This project allows you to manage files in an S3-compatible object storage service (e.g., Liara) using PHP. It includes features like uploading, deleting, listing files, generating pre-signed URLs, and accessing permanent URLs.

## Table of Contents
1. Prerequisites
2. Setup Instructions
   - Clone the Repository
   - Navigate to the Project Directory
   - Switch to the `object-storage` Branch
   - Rename `.env.example` to `.env`
   - Set Environment Variables
   - Install Dependencies
   - Run the Project Locally
3. Deploying to Liara
4. Contributing

---

## Prerequisites

Before you begin, ensure you have the following installed:
- Git: For cloning the repository.
- PHP 8.4: To run the PHP code.
- Composer: For managing PHP dependencies.
- Liara CLI: For deploying the project to Liara (optional but recommended).
- S3-Compatible Storage Credentials: Access key, secret key, endpoint, and bucket name.

---

## Setup Instructions

### Clone the Repository
To clone the project, run the following command in your terminal:

```
git clone https://github.com/liara-cloud/php-getting-started.git
```

### Navigate to the Project Directory
Change into the project directory:
```
cd php-getting-started
```

### Switch to the `object-storage` Branch
Switch to the branch containing the object storage management code:

```
git checkout object-storage
```

### Rename `.env.example` to `.env`
Rename the `.env.example` file to `.env`:

```
cp .env.example .env
```

### Set Environment Variables
Edit the `.env` file to include your S3-compatible storage credentials. Replace the placeholders with your actual values.


### Install Dependencies
Install the required PHP dependencies using Composer:

```
composer install
```

### Run the Project Locally
Start a local PHP development server:

```
php -S localhost:8000
```

Open your browser and navigate to:

```
http://localhost:8000/s3-storage.php
```

You should now see the dashboard where you can upload, delete, and manage files.

---

## Deploying to Liara

Follow these steps to deploy your project on Liara:

### Step 1: Install the Liara CLI

If you haven't already, you can just install the Liara CLI by following the instructions at [Liara Docs](https://docs.liara.ir/references/cli/install/).

### Step 2: Log in to Liara
Log in to your Liara account using the CLI:

```
liara login
```

### Step 3: Create a New App
Create a new app on Liara:

```
liara create --platform php
```

Follow the prompts to configure your app.

### Step 4: Add Environment Variables
Add your S3-compatible storage credentials as environment variables in the Liara console or via the CLI:

### Step 5: Deploy the Project
Deploy your project to Liara:

```
liara deploy
```

After deployment, Liara will provide you with a URL where your app is live. Open the URL in your browser to access the dashboard.

---

## Contributing

If you'd like to contribute to this project:
1. Fork the repository.
2. Create a new branch (git checkout -b feature/your-feature).
3. Commit your changes (git commit -m "Add your feature").
4. Push to the branch (git push origin feature/your-feature).
5. Open a pull request.

---

## Support

For any questions or issues, feel free to open an issue on GitHub or reach out to the maintainers.
