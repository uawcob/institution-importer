-- laravel just sucks with this:

SET ANSI_NULLS ON;

CREATE TABLE institutions
(
    id INT IDENTITY PRIMARY KEY,
    name NVARCHAR(255) NOT NULL UNIQUE,
    url NVARCHAR(255) NULL,
    latitude DECIMAL(8, 6) NULL,
    longitude DECIMAL(9, 6) NULL
);

CREATE UNIQUE NONCLUSTERED INDEX idx_unique_url_notnull
ON institutions(url)
WHERE url IS NOT NULL;
