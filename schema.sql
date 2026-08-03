-- 1. Users Table
-- Stores user credentials and profile details.
CREATE TABLE users (
 user_id INT AUTO_INCREMENT PRIMARY KEY,
 email VARCHAR(255) UNIQUE NOT NULL,
 password_hash VARCHAR(255) NOT NULL, -- Stores encrypted passwords for
security
 full_name VARCHAR(100) NOT NULL,
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
-- 2. Posts Table
-- Stores the blog posts created by users.
CREATE TABLE posts (
 post_id INT AUTO_INCREMENT PRIMARY KEY,
 user_id INT NOT NULL, -- Foreign key linking the post to its creator
 post_text TEXT NOT NULL,
 image_path VARCHAR(255), -- Stores the file pathway/URL of the uploaded
picture
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);

--fake users and posts
INSERT INTO users (email, password_hash, full_name) VALUES
('alihubailaly14@gmail.com', 'ef92b778bafe771e89245b89ecbc08a44a4e166c06659911881f383d4473e94f', 'Ali Hubail'),
('dr.instructor@university.edu', 'ef92b778bafe771e89245b89ecbc08a44a4e166c06659911881f383d4473e94f', 'Dr. Instructor'),
('sara.ahmed@example.com', 'ef92b778bafe771e89245b89ecbc08a44a4e166c06659911881f383d4473e94f', 'Sara Ahmed');

INSERT INTO posts (user_id, post_text, image_path) VALUES
(1, 'Just published my first blog post on Flogram! ✍️ I''ll be sharing my weekly tips on database normalization and advanced SQL queries. Let me know what topics you want me to write about next! #FirstBlog #TechBlogger', 'uploads/post_fake_image3.jpg'),
(2, 'Welcome to my new academic blog! I will be posting weekly updates, study guides, and important reminders for our upcoming Blackboard exams here. Make sure to follow the blog for the latest course materials. 📚', 'uploads/post_fake_image1.jpg'),
(1, 'Currently reading some amazing blogs on Flogram about presentation designs. The community here has so many great creative ideas for the reading competition! 💡📖', 'uploads/post_fake_image2.png'),
(3, 'Does anyone know any good science blogs on here? I am looking for detailed posts explaining solid-state physics and wavefunctions. Drop your Flogram blog recommendations below! 🔬👇', 'uploads/post_fake_image4.png');
