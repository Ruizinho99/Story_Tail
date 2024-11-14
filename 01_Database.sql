-- CRIAR DATABASE
CREATE DATABASE IF NOT EXISTS LP;

-- SELECIONAR A BASE DE DADOS
USE LP;

-- CRIAR TABELA 'user_types'
CREATE TABLE IF NOT EXISTS user_types (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_type VARCHAR(50) NOT NULL
);

-- CRIAR TABELA 'users' 
CREATE TABLE IF NOT EXISTS users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_type_id INT NOT NULL,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    user_name VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    user_photo_url VARCHAR(255),
    birth_date DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_type_id) REFERENCES user_types(id)
);

-- CRIAR TABELA 'authors'
CREATE TABLE IF NOT EXISTS authors (
    id INT PRIMARY KEY AUTO_INCREMENT,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    description TEXT,
    author_photo_url VARCHAR(255),
    nationality VARCHAR(50),
    added_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- CRIAR TABELA 'books'
CREATE TABLE IF NOT EXISTS books (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    cover_url VARCHAR(255),
    publication_year YEAR,
    age_group VARCHAR(50),
    is_active BOOLEAN DEFAULT TRUE,
    access_level VARCHAR(50) NOT NULL,
    added_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- CRIAR TABELA 'categories'
CREATE TABLE IF NOT EXISTS categories (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(50) NOT NULL
);

-- CRIAR TABELA 'book_category'
CREATE TABLE IF NOT EXISTS book_category (
    id INT PRIMARY KEY AUTO_INCREMENT,
    book_id INT NOT NULL,
    category_id INT NOT NULL,
    FOREIGN KEY (book_id) REFERENCES books(id) ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
);

-- CRIAR TABELA 'author_book'
CREATE TABLE IF NOT EXISTS author_book (
    author_id INT NOT NULL,
    book_id INT NOT NULL,
    PRIMARY KEY (author_id, book_id),
    FOREIGN KEY (author_id) REFERENCES authors(id) ON DELETE CASCADE,
    FOREIGN KEY (book_id) REFERENCES books(id) ON DELETE CASCADE
);

-- CRIAR TABELA 'ratings'
CREATE TABLE IF NOT EXISTS ratings (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    book_id INT NOT NULL,
    rating INT,
    comment TEXT,
    rating_date DATE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (book_id) REFERENCES books(id) ON DELETE CASCADE
);

-- CRIAR TABELA 'favorites'
CREATE TABLE IF NOT EXISTS favorites (
    book_id INT NOT NULL,
    user_id INT NOT NULL,
    added_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (book_id, user_id),
    FOREIGN KEY (book_id) REFERENCES books(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- CRIAR TABELA 'reading_progress'
CREATE TABLE IF NOT EXISTS reading_progress (
    book_id INT NOT NULL,
    user_id INT NOT NULL,
    current_page INT,
    last_accessed TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (book_id, user_id),
    FOREIGN KEY (book_id) REFERENCES books(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- CRIAR TABELA 'plans'
CREATE TABLE IF NOT EXISTS plans (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(50),
    access_level VARCHAR(50) NOT NULL,
    is_active BOOLEAN DEFAULT TRUE
);

-- CRIAR TABELA 'subscriptions'
CREATE TABLE IF NOT EXISTS subscriptions (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    plan_id INT NOT NULL,
    is_active BOOLEAN DEFAULT TRUE,
    start_date DATE,
    end_date DATE,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (plan_id) REFERENCES plans(id)
);

-- CRIAR TABELA 'activities'
CREATE TABLE IF NOT EXISTS activities (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    added_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- CRIAR TABELA 'activity_images'
CREATE TABLE IF NOT EXISTS activity_images (
    id INT PRIMARY KEY AUTO_INCREMENT,
    activity_id INT NOT NULL,
    image_url VARCHAR(255) NOT NULL,
    added_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (activity_id) REFERENCES activities(id) ON DELETE CASCADE
);

-- CRIAR TABELA 'activity_book'
CREATE TABLE IF NOT EXISTS activity_book (
    id INT PRIMARY KEY AUTO_INCREMENT,
    activity_id INT NOT NULL,
    book_id INT NOT NULL,
    FOREIGN KEY (activity_id) REFERENCES activities(id) ON DELETE CASCADE,
    FOREIGN KEY (book_id) REFERENCES books(id) ON DELETE CASCADE
);

-- CRIAR TABELA 'activity_book_user'
CREATE TABLE IF NOT EXISTS activity_book_user (
    id INT PRIMARY KEY AUTO_INCREMENT,
    activity_book_id INT NOT NULL,
    user_id INT NOT NULL,
    progress INT DEFAULT 0,
    FOREIGN KEY (activity_book_id) REFERENCES activity_book(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);




-- CRIAR TABELA 'activities'
CREATE TABLE IF NOT EXISTS activities (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    added_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- CRIAR TABELA 'activity_images'
CREATE TABLE IF NOT EXISTS activity_images (
    id INT PRIMARY KEY AUTO_INCREMENT,
    activity_id INT NOT NULL,
    image_url VARCHAR(255) NOT NULL,
    added_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (activity_id) REFERENCES activities(id) ON DELETE CASCADE
);

-- CRIAR TABELA 'activity_book'
CREATE TABLE IF NOT EXISTS activity_book (
    id INT PRIMARY KEY AUTO_INCREMENT,
    activity_id INT NOT NULL,
    book_id INT NOT NULL,
    FOREIGN KEY (activity_id) REFERENCES activities(id) ON DELETE CASCADE,
    FOREIGN KEY (book_id) REFERENCES books(id) ON DELETE CASCADE
);

-- CRIAR TABELA 'activity_book_user'
CREATE TABLE IF NOT EXISTS activity_book_user (
    id INT PRIMARY KEY AUTO_INCREMENT,
    activity_book_id INT NOT NULL,
    user_id INT NOT NULL,
    progress INT DEFAULT 0,
    FOREIGN KEY (activity_book_id) REFERENCES activity_book(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
