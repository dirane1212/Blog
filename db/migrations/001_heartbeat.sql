-- db/migrations/001_heartbeat.sql
CREATE TABLE IF NOT EXISTS app_heartbeat (
     id SERIAL PRIMARY KEY,
     created_at TIMESTAMPTZ NOT NULL DEFAULT now()
    );

INSERT INTO app_heartbeat DEFAULT VALUES;
