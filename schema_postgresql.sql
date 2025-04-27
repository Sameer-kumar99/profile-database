-- Create users table
CREATE TABLE users (
   user_id SERIAL PRIMARY KEY,
   name VARCHAR(128),
   email VARCHAR(128),
   password VARCHAR(128)
);

-- Create indexes
CREATE INDEX idx_users_email ON users(email);
CREATE INDEX idx_users_password ON users(password);

-- Insert default user
INSERT INTO users (name, email, password)
    VALUES ('UMSI', 'umsi@umich.edu', '1a52e17fa899cf40fb04cfc42e6352f1');

-- Create Profile table
CREATE TABLE Profile (
  profile_id SERIAL PRIMARY KEY,
  user_id INTEGER NOT NULL,
  first_name TEXT,
  last_name TEXT,
  email TEXT,
  headline TEXT,
  summary TEXT,
  
  CONSTRAINT profile_ibfk_2
        FOREIGN KEY (user_id)
        REFERENCES users (user_id)
        ON DELETE CASCADE
);