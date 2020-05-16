create table users
(
	id serial not null,
	name varchar(50) not null,
	email varchar(80) not null,
	password varchar(50) not null
);

create unique index users_email_uindex
	on users (email);

create unique index users_id_uindex
	on users (id);

alter table users
	add constraint users_pk
		primary key (id);

create table speakers
(
	id serial not null
		constraint speakers_pk
			primary key,
	name varchar(50) not null
);

create table conferences
(
	id serial not null
		constraint conferences_pk
			primary key,
	conference_year smallserial not null,
	conference_month smallserial not null
);

create unique index conferences_conference_year_conference_month_uindex
	on conferences (conference_year, conference_month);
	
create table sessions
(
	id serial not null
		constraint sessions_pk
			primary key,
	conference_id int not null
		constraint sessions_conferences_id_fk
			references conferences
				on update cascade on delete cascade
);

create table discourses
(
	id serial not null
		constraint discourses_pk
			primary key,
	speaker_id int not null
		constraint discourses_speakers_id_fk
			references speakers
				on update cascade on delete cascade,
	session_id int not null
		constraint discourses_sessions_id_fk
			references sessions
				on update cascade on delete cascade,
	title varchar(120) not null,
	discourse_text text not null
);

create table notes
(
	id serial not null
		constraint notes_pk
			primary key,
	user_id int not null
		constraint notes_users_id_fk
			references users
				on update cascade on delete cascade,
	discourse_id int not null
		constraint notes_discourses_id_fk
			references discourses
				on update cascade on delete cascade,
	note text not null,
	created_at timestamp not null
);

INSERT INTO users (name, email, password) values ('Nick Routsong', 'routy@byui.edu', 'SuperSecretHash1');
INSERT INTO users (name, email, password) values ('Brian Earl', 'be@byui.edu', 'SuperSecretHash2');
INSERT INTO users (name, email, password) values ('Briana Olsen', 'bo@byui.edu', 'SuperSecretHash3');
INSERT INTO users (name, email, password) values ('Nate McCoard', 'nm@byui.edu', 'SuperSecretHash4');

INSERT INTO speakers (name) values ('President Nelson'), ('Jeffrey Holland'), ('David Bednar'), ('Neil Anderson'), ('Henry Eyring'), ('M. Russell Ballard');

INSERT INTO conferences (conference_year, conference_month) values (2018, 04), (2018, 10), (2019, 04), (2019, 10), (2020, 04);

INSERT INTO sessions (conference_id, title) values
    (1, 'Saturday Morning'), (1, 'Saturday Afternoon'), (1, 'Priesthood'), (1, 'Sunday Morning'), (1, 'Sunday Afternoon'),
    (2, 'Saturday Morning'), (2, 'Saturday Afternoon'), (2, 'Womens'), (2, 'Sunday Morning'), (2, 'Sunday Afternoon'),
    (3, 'Saturday Morning'), (3, 'Saturday Afternoon'), (3, 'Priesthood'), (3, 'Sunday Morning'), (3, 'Sunday Afternoon'),
    (4, 'Saturday Morning'), (4, 'Saturday Afternoon'), (4, 'Womens'), (4, 'Sunday Morning'), (4, 'Sunday Afternoon'),
    (5, 'Saturday Morning'), (5, 'Saturday Afternoon'), (5, 'Evening'), (5, 'Sunday Morning'), (5, 'Sunday Afternoon');
    
INSERT INTO discourses (speaker_id, session_id, title, discourse_text) values
    (1, 1, 'Title 1', 'Discourse 1 Text'),
    (2, 1, 'Title 2', 'Discourse 2 Text'),
    (3, 1, 'Title 2', 'Discourse 2 Text'),
    (4, 2, 'Title 2', 'Discourse 2 Text'),
    (5, 2, 'Title 2', 'Discourse 2 Text'),
    (6, 2, 'Title 2', 'Discourse 2 Text');
    
INSERT INTO notes (user_id, discourse_id, note, created_at) values
    (1, 1, 'Note 1', NOW()),
    (1, 1, 'Note 2', NOW()),
    (2, 1, 'Note 2', NOW()),
    (2, 2, 'Note 2', NOW()),
    (3, 2, 'Note 2', NOW()),
    (3, 2, 'Note 2', NOW()),
    (4, 3, 'Note 2', NOW()),
    (4, 3, 'Note 2', NOW());
    
    
SELECT n.id,
       n.note,
       u.name,
       d.title,
       s.title,
       concat_ws('/', c.conference_month, c.conference_year) as conference,
       n.created_at
FROM notes as n
INNER JOIN users as u
    ON (n.user_id = u.id)
INNER JOIN discourses as d
    ON (n.discourse_id = d.id)
INNER JOIN sessions as s
    ON (d.session_id = s.id)
INNER JOIN conferences as c
    ON (s.conference_id = c.id);