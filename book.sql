--
-- PostgreSQL database dump
--

-- Dumped from database version 9.5.14
-- Dumped by pg_dump version 9.5.14

-- Started on 2018-09-22 23:50:46 EDT

SET statement_timeout = 0;
SET lock_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET client_min_messages = warning;
SET row_security = off;

--
-- TOC entry 9 (class 2615 OID 18211)
-- Name: book; Type: SCHEMA; Schema: -; Owner: jsnook
--

CREATE SCHEMA book;


ALTER SCHEMA book OWNER TO jsnook;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- TOC entry 220 (class 1259 OID 18214)
-- Name: super; Type: TABLE; Schema: book; Owner: jsnook
--

CREATE TABLE book.super (
    id integer NOT NULL,
    title character varying NOT NULL,
    created_at timestamp without time zone DEFAULT now() NOT NULL
);


ALTER TABLE book.super OWNER TO jsnook;

--
-- TOC entry 221 (class 1259 OID 18224)
-- Name: updatable; Type: TABLE; Schema: book; Owner: jsnook
--

CREATE TABLE book.updatable (
    created_by integer,
    updated_at timestamp without time zone NOT NULL,
    updated_by integer
)
INHERITS (book.super);


ALTER TABLE book.updatable OWNER TO jsnook;

--
-- TOC entry 222 (class 1259 OID 18242)
-- Name: book; Type: TABLE; Schema: book; Owner: jsnook
--

CREATE TABLE book.book (
    slug character varying NOT NULL,
    key character varying NOT NULL,
    toc integer[] DEFAULT '{}'::integer[]
)
INHERITS (book.updatable);


ALTER TABLE book.book OWNER TO jsnook;

--
-- TOC entry 223 (class 1259 OID 18253)
-- Name: chapter; Type: TABLE; Schema: book; Owner: jsnook
--

CREATE TABLE book.chapter (
    toc integer[] DEFAULT '{}'::integer[] NOT NULL,
    book_slug character varying NOT NULL,
    content text NOT NULL
)
INHERITS (book.updatable);


ALTER TABLE book.chapter OWNER TO jsnook;

--
-- TOC entry 225 (class 1259 OID 18270)
-- Name: figure; Type: TABLE; Schema: book; Owner: jsnook
--

CREATE TABLE book.figure (
    image bytea NOT NULL,
    book_slug character varying NOT NULL
)
INHERITS (book.super);


ALTER TABLE book.figure OWNER TO jsnook;

--
-- TOC entry 226 (class 1259 OID 18285)
-- Name: link; Type: TABLE; Schema: book; Owner: jsnook
--

CREATE TABLE book.link (
    url character varying NOT NULL,
    book_slug character varying NOT NULL
)
INHERITS (book.super);


ALTER TABLE book.link OWNER TO jsnook;

--
-- TOC entry 224 (class 1259 OID 18262)
-- Name: section; Type: TABLE; Schema: book; Owner: jsnook
--

CREATE TABLE book.section (
    chapter_id integer NOT NULL,
    content text NOT NULL
)
INHERITS (book.updatable);


ALTER TABLE book.section OWNER TO jsnook;

--
-- TOC entry 219 (class 1259 OID 18212)
-- Name: super_id_seq; Type: SEQUENCE; Schema: book; Owner: jsnook
--

CREATE SEQUENCE book.super_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE book.super_id_seq OWNER TO jsnook;

--
-- TOC entry 2365 (class 0 OID 0)
-- Dependencies: 219
-- Name: super_id_seq; Type: SEQUENCE OWNED BY; Schema: book; Owner: jsnook
--

ALTER SEQUENCE book.super_id_seq OWNED BY book.super.id;


--
-- TOC entry 2209 (class 2604 OID 18245)
-- Name: id; Type: DEFAULT; Schema: book; Owner: jsnook
--

ALTER TABLE ONLY book.book ALTER COLUMN id SET DEFAULT nextval('book.super_id_seq'::regclass);


--
-- TOC entry 2210 (class 2604 OID 18246)
-- Name: created_at; Type: DEFAULT; Schema: book; Owner: jsnook
--

ALTER TABLE ONLY book.book ALTER COLUMN created_at SET DEFAULT now();


--
-- TOC entry 2212 (class 2604 OID 18256)
-- Name: id; Type: DEFAULT; Schema: book; Owner: jsnook
--

ALTER TABLE ONLY book.chapter ALTER COLUMN id SET DEFAULT nextval('book.super_id_seq'::regclass);


--
-- TOC entry 2213 (class 2604 OID 18257)
-- Name: created_at; Type: DEFAULT; Schema: book; Owner: jsnook
--

ALTER TABLE ONLY book.chapter ALTER COLUMN created_at SET DEFAULT now();


--
-- TOC entry 2217 (class 2604 OID 18273)
-- Name: id; Type: DEFAULT; Schema: book; Owner: jsnook
--

ALTER TABLE ONLY book.figure ALTER COLUMN id SET DEFAULT nextval('book.super_id_seq'::regclass);


--
-- TOC entry 2218 (class 2604 OID 18274)
-- Name: created_at; Type: DEFAULT; Schema: book; Owner: jsnook
--

ALTER TABLE ONLY book.figure ALTER COLUMN created_at SET DEFAULT now();


--
-- TOC entry 2219 (class 2604 OID 18288)
-- Name: id; Type: DEFAULT; Schema: book; Owner: jsnook
--

ALTER TABLE ONLY book.link ALTER COLUMN id SET DEFAULT nextval('book.super_id_seq'::regclass);


--
-- TOC entry 2220 (class 2604 OID 18289)
-- Name: created_at; Type: DEFAULT; Schema: book; Owner: jsnook
--

ALTER TABLE ONLY book.link ALTER COLUMN created_at SET DEFAULT now();


--
-- TOC entry 2215 (class 2604 OID 18265)
-- Name: id; Type: DEFAULT; Schema: book; Owner: jsnook
--

ALTER TABLE ONLY book.section ALTER COLUMN id SET DEFAULT nextval('book.super_id_seq'::regclass);


--
-- TOC entry 2216 (class 2604 OID 18266)
-- Name: created_at; Type: DEFAULT; Schema: book; Owner: jsnook
--

ALTER TABLE ONLY book.section ALTER COLUMN created_at SET DEFAULT now();


--
-- TOC entry 2205 (class 2604 OID 18217)
-- Name: id; Type: DEFAULT; Schema: book; Owner: jsnook
--

ALTER TABLE ONLY book.super ALTER COLUMN id SET DEFAULT nextval('book.super_id_seq'::regclass);


--
-- TOC entry 2207 (class 2604 OID 18227)
-- Name: id; Type: DEFAULT; Schema: book; Owner: jsnook
--

ALTER TABLE ONLY book.updatable ALTER COLUMN id SET DEFAULT nextval('book.super_id_seq'::regclass);


--
-- TOC entry 2208 (class 2604 OID 18228)
-- Name: created_at; Type: DEFAULT; Schema: book; Owner: jsnook
--

ALTER TABLE ONLY book.updatable ALTER COLUMN created_at SET DEFAULT now();


--
-- TOC entry 2355 (class 0 OID 18242)
-- Dependencies: 222
-- Data for Name: book; Type: TABLE DATA; Schema: book; Owner: jsnook
--

COPY book.book (id, title, created_at, created_by, updated_at, updated_by, slug, key, toc) FROM stdin;
\.


--
-- TOC entry 2356 (class 0 OID 18253)
-- Dependencies: 223
-- Data for Name: chapter; Type: TABLE DATA; Schema: book; Owner: jsnook
--

COPY book.chapter (id, title, created_at, created_by, updated_at, updated_by, toc, book_slug, content) FROM stdin;
\.


--
-- TOC entry 2358 (class 0 OID 18270)
-- Dependencies: 225
-- Data for Name: figure; Type: TABLE DATA; Schema: book; Owner: jsnook
--

COPY book.figure (id, title, created_at, image, book_slug) FROM stdin;
\.


--
-- TOC entry 2359 (class 0 OID 18285)
-- Dependencies: 226
-- Data for Name: link; Type: TABLE DATA; Schema: book; Owner: jsnook
--

COPY book.link (id, title, created_at, url, book_slug) FROM stdin;
\.


--
-- TOC entry 2357 (class 0 OID 18262)
-- Dependencies: 224
-- Data for Name: section; Type: TABLE DATA; Schema: book; Owner: jsnook
--

COPY book.section (id, title, created_at, created_by, updated_at, updated_by, chapter_id, content) FROM stdin;
\.


--
-- TOC entry 2353 (class 0 OID 18214)
-- Dependencies: 220
-- Data for Name: super; Type: TABLE DATA; Schema: book; Owner: jsnook
--

COPY book.super (id, title, created_at) FROM stdin;
\.


--
-- TOC entry 2366 (class 0 OID 0)
-- Dependencies: 219
-- Name: super_id_seq; Type: SEQUENCE SET; Schema: book; Owner: jsnook
--

SELECT pg_catalog.setval('book.super_id_seq', 1, false);


--
-- TOC entry 2354 (class 0 OID 18224)
-- Dependencies: 221
-- Data for Name: updatable; Type: TABLE DATA; Schema: book; Owner: jsnook
--

COPY book.updatable (id, title, created_at, created_by, updated_at, updated_by) FROM stdin;
\.


--
-- TOC entry 2224 (class 2606 OID 18252)
-- Name: book_pkey; Type: CONSTRAINT; Schema: book; Owner: jsnook
--

ALTER TABLE ONLY book.book
    ADD CONSTRAINT book_pkey PRIMARY KEY (slug);


--
-- TOC entry 2226 (class 2606 OID 18301)
-- Name: chapter_pkey; Type: CONSTRAINT; Schema: book; Owner: jsnook
--

ALTER TABLE ONLY book.chapter
    ADD CONSTRAINT chapter_pkey PRIMARY KEY (id);


--
-- TOC entry 2230 (class 2606 OID 18279)
-- Name: figure_pkey; Type: CONSTRAINT; Schema: book; Owner: jsnook
--

ALTER TABLE ONLY book.figure
    ADD CONSTRAINT figure_pkey PRIMARY KEY (id);


--
-- TOC entry 2232 (class 2606 OID 18294)
-- Name: link_pkey; Type: CONSTRAINT; Schema: book; Owner: jsnook
--

ALTER TABLE ONLY book.link
    ADD CONSTRAINT link_pkey PRIMARY KEY (id);


--
-- TOC entry 2228 (class 2606 OID 18308)
-- Name: section_pkey; Type: CONSTRAINT; Schema: book; Owner: jsnook
--

ALTER TABLE ONLY book.section
    ADD CONSTRAINT section_pkey PRIMARY KEY (id);


--
-- TOC entry 2222 (class 2606 OID 18223)
-- Name: super_pkey; Type: CONSTRAINT; Schema: book; Owner: jsnook
--

ALTER TABLE ONLY book.super
    ADD CONSTRAINT super_pkey PRIMARY KEY (id);


--
-- TOC entry 2233 (class 2606 OID 18302)
-- Name: chapter_book_fkey; Type: FK CONSTRAINT; Schema: book; Owner: jsnook
--

ALTER TABLE ONLY book.chapter
    ADD CONSTRAINT chapter_book_fkey FOREIGN KEY (book_slug) REFERENCES book.book(slug);


--
-- TOC entry 2235 (class 2606 OID 18314)
-- Name: figure_book_fkey; Type: FK CONSTRAINT; Schema: book; Owner: jsnook
--

ALTER TABLE ONLY book.figure
    ADD CONSTRAINT figure_book_fkey FOREIGN KEY (book_slug) REFERENCES book.book(slug) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 2236 (class 2606 OID 18295)
-- Name: link_book_fkey; Type: FK CONSTRAINT; Schema: book; Owner: jsnook
--

ALTER TABLE ONLY book.link
    ADD CONSTRAINT link_book_fkey FOREIGN KEY (book_slug) REFERENCES book.book(slug) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 2234 (class 2606 OID 18309)
-- Name: section_chapter_fkey; Type: FK CONSTRAINT; Schema: book; Owner: jsnook
--

ALTER TABLE ONLY book.section
    ADD CONSTRAINT section_chapter_fkey FOREIGN KEY (chapter_id) REFERENCES book.chapter(id) ON UPDATE CASCADE ON DELETE CASCADE;


-- Completed on 2018-09-22 23:50:47 EDT

--
-- PostgreSQL database dump complete
--

