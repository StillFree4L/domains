CREATE DATABASE db_mycms
  WITH OWNER = postgres
    ENCODING = 'UTF8';

-- USERS --

CREATE TABLE public.users (
  id SERIAL NOT NULL,
  login VARCHAR(32) NOT NULL,
  password VARCHAR(32) NOT NULL,
  role VARCHAR DEFAULT 'user' NOT NULL,
  last_name VARCHAR(64) NOT NULL,
  first_name VARCHAR(64) NOT NULL,
  middle_name VARCHAR(64),
  sex SMALLINT DEFAULT 1 NOT NULL,
  birthdate INTEGER,
  ts INTEGER,
  PRIMARY KEY(id)
) WITHOUT OIDS;

ALTER TABLE public.users
  ALTER COLUMN id SET STATISTICS 0;

ALTER TABLE public.users
  ALTER COLUMN login SET STATISTICS 0;

ALTER TABLE public.users
  ALTER COLUMN password SET STATISTICS 0;

ALTER TABLE public.users
  ALTER COLUMN role SET STATISTICS 0;

ALTER TABLE public.users
  ALTER COLUMN last_name SET STATISTICS 0;

ALTER TABLE public.users
  ALTER COLUMN first_name SET STATISTICS 0;

ALTER TABLE public.users
  ALTER COLUMN middle_name SET STATISTICS 0;

ALTER TABLE public.users
  ALTER COLUMN sex SET STATISTICS 0;

ALTER TABLE public.users
  ALTER COLUMN birthdate SET STATISTICS 0;

ALTER TABLE public.users
  ALTER COLUMN ts SET STATISTICS 0;



-- INSTANCES --

CREATE TABLE public.instances_ru (
  id SERIAL NOT NULL,
  caption VARCHAR(1000),
  preview VARCHAR(2000),
  body TEXT,
  type SMALLINT DEFAULT 1 NOT NULL,
  state SMALLINT DEFAULT 1 NOT NULL,
  ts INTEGER,
  is_c SMALLINT DEFAULT 0 NOT NULL,
  PRIMARY KEY(id)
) WITHOUT OIDS;

ALTER TABLE public.instances_ru
  ALTER COLUMN id SET STATISTICS 0;

ALTER TABLE public.instances_ru
  ALTER COLUMN caption SET STATISTICS 0;

ALTER TABLE public.instances_ru
  ALTER COLUMN preview SET STATISTICS 0;

ALTER TABLE public.instances_ru
  ALTER COLUMN body SET STATISTICS 0;

ALTER TABLE public.instances_ru
  ALTER COLUMN type SET STATISTICS 0;

ALTER TABLE public.instances_ru
  ALTER COLUMN state SET STATISTICS 0;

ALTER TABLE public.instances_ru
  ALTER COLUMN ts SET STATISTICS 0;

ALTER TABLE public.instances_ru
  ALTER COLUMN is_c SET STATISTICS 0;

COMMENT ON COLUMN public.instances_ru.type
IS '1 - record,
2 - page,
3 - category';

COMMENT ON COLUMN public.instances_ru.state
IS '1 - sketch,
2 - published,
3 - deleted';

ALTER TABLE public.instances_ru
  ADD COLUMN ref VARCHAR;

-- INSTANCE RELATIONS --
CREATE TABLE public.instance_relations (
  id SERIAL NOT NULL,
  r_id INTEGER NOT NULL,
  p_id INTEGER NOT NULL,
  ts INTEGER,
  PRIMARY KEY(id)
) WITHOUT OIDS;

ALTER TABLE public.instance_relations
  ALTER COLUMN id SET STATISTICS 0;

ALTER TABLE public.instance_relations
  ALTER COLUMN r_id SET STATISTICS 0;

ALTER TABLE public.instance_relations
  ALTER COLUMN p_id SET STATISTICS 0;

ALTER TABLE public.instance_relations
  ALTER COLUMN ts SET STATISTICS 0;


-- MENU --

CREATE TABLE public.menu (
  id SERIAL NOT NULL,
  "group" VARCHAR(255) NOT NULL,
  "position" INTEGER DEFAULT 1 NOT NULL,
  parent INTEGER,
  instance_id INTEGER NOT NULL,
  ts INTEGER,
  PRIMARY KEY(id)
) WITHOUT OIDS;

ALTER TABLE public.menu
  ALTER COLUMN id SET STATISTICS 0;

ALTER TABLE public.menu
  ALTER COLUMN "group" SET STATISTICS 0;

ALTER TABLE public.menu
  ALTER COLUMN "position" SET STATISTICS 0;

ALTER TABLE public.menu
  ALTER COLUMN parent SET STATISTICS 0;

ALTER TABLE public.menu
  ALTER COLUMN instance_id SET STATISTICS 0;

ALTER TABLE public.menu
  ALTER COLUMN ts SET STATISTICS 0;

ALTER TABLE public.menu
  ADD CONSTRAINT menu_fk FOREIGN KEY (instance_id)
    REFERENCES public.instances_ru(id)
    ON DELETE CASCADE
    ON UPDATE CASCADE
    NOT DEFERRABLE;