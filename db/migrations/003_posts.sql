-- db/migrations/003_posts.sql

-- Sections fixed for now
DO $$
    BEGIN
        IF NOT EXISTS (SELECT 1 FROM pg_type WHERE typname = 'section_type') THEN
            CREATE TYPE section_type AS ENUM ('politics', 'economics_finance', 'social_affairs');
        END IF;
    END
$$;

-- Status enum
DO $$
    BEGIN
        IF NOT EXISTS (SELECT 1 FROM pg_type WHERE typname = 'post_status') THEN
            CREATE TYPE post_status AS ENUM ('draft', 'published');
        END IF;
    END
$$;

CREATE TABLE IF NOT EXISTS posts (
                                     id BIGSERIAL PRIMARY KEY,
                                     author_id BIGINT NOT NULL REFERENCES users(id) ON DELETE CASCADE,
                                     section section_type NOT NULL,
                                     title TEXT NOT NULL,
                                     slug TEXT NOT NULL UNIQUE,
                                     excerpt TEXT,
                                     body_md TEXT NOT NULL,
                                     body_html TEXT,
                                     access_level TEXT NOT NULL DEFAULT 'free' CHECK (access_level IN ('free','premium')),
                                     status post_status NOT NULL DEFAULT 'draft',
                                     published_at TIMESTAMPTZ,
                                     created_at TIMESTAMPTZ NOT NULL DEFAULT now(),
                                     updated_at TIMESTAMPTZ NOT NULL DEFAULT now(),
                                     canonical_url TEXT
);

CREATE INDEX IF NOT EXISTS idx_posts_published_at ON posts(published_at DESC);
