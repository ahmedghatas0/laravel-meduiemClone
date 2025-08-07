# Medium Clone – Laravel Project

A blog platform inspired by [Medium.com](https://medium.com), built with Laravel 11 as part of a learning project.

## ✅ Features

- User registration and login (with email verification)
- Create / edit / delete posts
- Follow / Unfollow users
- Like / Unlike posts
- Show posts only from followed users
- Filter posts by category
- Upload and display images
- Pagination
- Public user profiles
- Publish / draft control
- Responsive UI using Tailwind + Flowbite

## 🛠️ Tech Stack

- Laravel 11
- Laravel Breeze
- MySQL
- Tailwind CSS
- Flowbite
- Mailpit (for testing email verification)
- Tinker (for testing models)

## ⚙️ Installation Steps

```bash
git clone https://github.com/your-username/medium-clone.git
cd medium-clone
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
npm install && npm run dev
php artisan serve

## 🙋‍♂️ Author

- **Ahmed Ghatas**
- Aspiring PHP & Laravel Developer
- [GitHub]https://github.com/ahmedghatas0
