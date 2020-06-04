create table topics
(
	id serial not null
		constraint topics_pk
			primary key,
	name varchar(40) not null
);

create table scripture_topic
(
	scripture_id int not null
		constraint scripture_topic_scriptures_id_fk
			references scriptures,
	topic_id int
		constraint scripture_topic_topics_id_fk
			references topics
);

