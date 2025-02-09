DROP TABLE IF EXISTS login_session;

CREATE TABLE login_session(
    user_id INT NOT NULL,
    session_string VARCHAR(200) NOT NULL,
    created_by TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);