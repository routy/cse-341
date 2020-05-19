CREATE TABLE public.scriptures
(
    id SERIAL PRIMARY KEY NOT NULL,
    book VARCHAR(40) NOT NULL,
    chapter INT NOT NULL,
    verse INT NOT NULL,
    content TEXT NOT NULL
);