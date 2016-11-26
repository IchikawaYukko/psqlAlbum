--
-- PostgreSQL database dump
--

-- Dumped from database version 9.5.2
-- Dumped by pg_dump version 9.5.2

SET statement_timeout = 0;
SET lock_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET client_min_messages = warning;
SET row_security = off;

SET search_path = public, pg_catalog;

--
-- Data for Name: jpeg_orientation; Type: TABLE DATA; Schema: public; Owner: yuriko
--

COPY jpeg_orientation (orientation, description) FROM stdin;
1	No Change
3	Rotate 180 degrees
4	Mirror
5	?
6	Rotate 90 degrees CW
7	?
8	Rotate 270 degrees CW
2	Upside Down
\.


--
-- PostgreSQL database dump complete
--

