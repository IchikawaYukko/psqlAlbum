--
-- PostgreSQL database dump
--

SET statement_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = off;
SET check_function_bodies = false;
SET client_min_messages = warning;
SET escape_string_warning = off;

SET search_path = public, pg_catalog;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: album; Type: TABLE; Schema: public; Owner: yuriko; Tablespace: 
--

CREATE TABLE album (
    id integer NOT NULL,
    date_begin date NOT NULL,
    date_end date NOT NULL,
    title text,
    description text,
    path_photo character varying(64) NOT NULL,
    CONSTRAINT album_check CHECK ((date_end >= date_begin)),
    CONSTRAINT album_date_begin_check CHECK ((date_begin < ('now'::text)::date)),
    CONSTRAINT album_date_end_check CHECK ((date_end < ('now'::text)::date))
);


ALTER TABLE public.album OWNER TO yuriko;

--
-- Name: album_id_seq; Type: SEQUENCE; Schema: public; Owner: yuriko
--

CREATE SEQUENCE album_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.album_id_seq OWNER TO yuriko;

--
-- Name: album_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: yuriko
--

ALTER SEQUENCE album_id_seq OWNED BY album.id;


--
-- Name: gpx; Type: TABLE; Schema: public; Owner: yuriko; Tablespace: 
--

CREATE TABLE gpx (
    id integer NOT NULL,
    date date NOT NULL,
    filename character varying(64) NOT NULL,
    CONSTRAINT gpx_date_check CHECK ((date < ('now'::text)::date))
);


ALTER TABLE public.gpx OWNER TO yuriko;

--
-- Name: gpx_id_seq; Type: SEQUENCE; Schema: public; Owner: yuriko
--

CREATE SEQUENCE gpx_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.gpx_id_seq OWNER TO yuriko;

--
-- Name: gpx_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: yuriko
--

ALTER SEQUENCE gpx_id_seq OWNED BY gpx.id;


--
-- Name: jpeg_orientation; Type: TABLE; Schema: public; Owner: yuriko; Tablespace: 
--

CREATE TABLE jpeg_orientation (
    orientation integer NOT NULL,
    description text NOT NULL,
    CONSTRAINT jpeg_orientation_orientation_check CHECK (((orientation > 0) AND (orientation <= 8)))
);


ALTER TABLE public.jpeg_orientation OWNER TO yuriko;

--
-- Name: photo; Type: TABLE; Schema: public; Owner: yuriko; Tablespace: 
--

CREATE TABLE photo (
    id integer NOT NULL,
    filename character varying(64) NOT NULL,
    datetaken date NOT NULL,
    title text,
    description text,
    flag character(3),
    orientation smallint DEFAULT 1,
    CONSTRAINT photo_datetaken_check CHECK ((datetaken < ('now'::text)::date))
);


ALTER TABLE public.photo OWNER TO yuriko;

--
-- Name: photo_id_seq; Type: SEQUENCE; Schema: public; Owner: yuriko
--

CREATE SEQUENCE photo_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.photo_id_seq OWNER TO yuriko;

--
-- Name: photo_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: yuriko
--

ALTER SEQUENCE photo_id_seq OWNED BY photo.id;


--
-- Name: photo_view; Type: VIEW; Schema: public; Owner: yuriko
--

CREATE VIEW photo_view AS
    SELECT photo.id, photo.filename, photo.datetaken, photo.title, photo.description, photo.orientation, album.path_photo FROM photo, album WHERE (((photo.datetaken >= album.date_begin) AND (photo.datetaken <= album.date_end)) AND (photo.flag IS NULL));


ALTER TABLE public.photo_view OWNER TO yuriko;

--
-- Name: sound; Type: TABLE; Schema: public; Owner: yuriko; Tablespace: 
--

CREATE TABLE sound (
    id integer NOT NULL,
    filename character varying(64) NOT NULL,
    datetaken date NOT NULL,
    title text,
    description text,
    length integer NOT NULL,
    flag character(3),
    CONSTRAINT sound_datetaken_check CHECK ((datetaken < ('now'::text)::date)),
    CONSTRAINT sound_length_check CHECK ((length > 0))
);


ALTER TABLE public.sound OWNER TO yuriko;

--
-- Name: sound_id_seq; Type: SEQUENCE; Schema: public; Owner: yuriko
--

CREATE SEQUENCE sound_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.sound_id_seq OWNER TO yuriko;

--
-- Name: sound_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: yuriko
--

ALTER SEQUENCE sound_id_seq OWNED BY sound.id;


--
-- Name: video; Type: TABLE; Schema: public; Owner: yuriko; Tablespace: 
--

CREATE TABLE video (
    id integer NOT NULL,
    filename character varying(64) NOT NULL,
    datetaken date NOT NULL,
    title text,
    description text,
    length integer NOT NULL,
    flag character(3),
    youtube_id text,
    CONSTRAINT video_date_check CHECK ((datetaken < ('now'::text)::date)),
    CONSTRAINT video_length_check CHECK ((length > 0))
);


ALTER TABLE public.video OWNER TO yuriko;

--
-- Name: video_id_seq; Type: SEQUENCE; Schema: public; Owner: yuriko
--

CREATE SEQUENCE video_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.video_id_seq OWNER TO yuriko;

--
-- Name: video_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: yuriko
--

ALTER SEQUENCE video_id_seq OWNED BY video.id;


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: yuriko
--

ALTER TABLE ONLY album ALTER COLUMN id SET DEFAULT nextval('album_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: yuriko
--

ALTER TABLE ONLY gpx ALTER COLUMN id SET DEFAULT nextval('gpx_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: yuriko
--

ALTER TABLE ONLY photo ALTER COLUMN id SET DEFAULT nextval('photo_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: yuriko
--

ALTER TABLE ONLY sound ALTER COLUMN id SET DEFAULT nextval('sound_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: yuriko
--

ALTER TABLE ONLY video ALTER COLUMN id SET DEFAULT nextval('video_id_seq'::regclass);


--
-- Name: album_pkey; Type: CONSTRAINT; Schema: public; Owner: yuriko; Tablespace: 
--

ALTER TABLE ONLY album
    ADD CONSTRAINT album_pkey PRIMARY KEY (id);


--
-- Name: gpx_pkey; Type: CONSTRAINT; Schema: public; Owner: yuriko; Tablespace: 
--

ALTER TABLE ONLY gpx
    ADD CONSTRAINT gpx_pkey PRIMARY KEY (id);


--
-- Name: jpeg_orientation_pkey; Type: CONSTRAINT; Schema: public; Owner: yuriko; Tablespace: 
--

ALTER TABLE ONLY jpeg_orientation
    ADD CONSTRAINT jpeg_orientation_pkey PRIMARY KEY (orientation);


--
-- Name: photo_pkey; Type: CONSTRAINT; Schema: public; Owner: yuriko; Tablespace: 
--

ALTER TABLE ONLY photo
    ADD CONSTRAINT photo_pkey PRIMARY KEY (id);


--
-- Name: sound_pkey; Type: CONSTRAINT; Schema: public; Owner: yuriko; Tablespace: 
--

ALTER TABLE ONLY sound
    ADD CONSTRAINT sound_pkey PRIMARY KEY (id);


--
-- Name: video_pkey; Type: CONSTRAINT; Schema: public; Owner: yuriko; Tablespace: 
--

ALTER TABLE ONLY video
    ADD CONSTRAINT video_pkey PRIMARY KEY (id);


--
-- Name: photo_orientation_fkey; Type: FK CONSTRAINT; Schema: public; Owner: yuriko
--

ALTER TABLE ONLY photo
    ADD CONSTRAINT photo_orientation_fkey FOREIGN KEY (orientation) REFERENCES jpeg_orientation(orientation);


--
-- Name: public; Type: ACL; Schema: -; Owner: postgres
--

REVOKE ALL ON SCHEMA public FROM PUBLIC;
REVOKE ALL ON SCHEMA public FROM postgres;
GRANT ALL ON SCHEMA public TO postgres;
GRANT ALL ON SCHEMA public TO PUBLIC;


--
-- Name: album; Type: ACL; Schema: public; Owner: yuriko
--

REVOKE ALL ON TABLE album FROM PUBLIC;
REVOKE ALL ON TABLE album FROM yuriko;
GRANT ALL ON TABLE album TO yuriko;
GRANT SELECT ON TABLE album TO readonly;


--
-- Name: gpx; Type: ACL; Schema: public; Owner: yuriko
--

REVOKE ALL ON TABLE gpx FROM PUBLIC;
REVOKE ALL ON TABLE gpx FROM yuriko;
GRANT ALL ON TABLE gpx TO yuriko;
GRANT SELECT ON TABLE gpx TO readonly;


--
-- Name: photo_view; Type: ACL; Schema: public; Owner: yuriko
--

REVOKE ALL ON TABLE photo_view FROM PUBLIC;
REVOKE ALL ON TABLE photo_view FROM yuriko;
GRANT ALL ON TABLE photo_view TO yuriko;
GRANT SELECT ON TABLE photo_view TO readonly;


--
-- Name: video; Type: ACL; Schema: public; Owner: yuriko
--

REVOKE ALL ON TABLE video FROM PUBLIC;
REVOKE ALL ON TABLE video FROM yuriko;
GRANT ALL ON TABLE video TO yuriko;
GRANT SELECT ON TABLE video TO readonly;


--
-- PostgreSQL database dump complete
--

