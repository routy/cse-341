create table if not exists users
(
	id serial not null
		constraint users_pk
			primary key,
	first_name varchar(30) not null,
	last_name varchar(30) not null,
	email varchar(80) not null,
	password varchar(80) not null,
	created_at timestamp default CURRENT_TIMESTAMP not null,
	updated_at timestamp default CURRENT_TIMESTAMP not null
);

alter table users owner to vimujnexvsdlyl;

create unique index if not exists users_email_uindex
	on users (email);

create table if not exists statuses
(
	id smallserial not null
		constraint status_pk
			primary key,
	name varchar(30) not null
);

alter table statuses owner to vimujnexvsdlyl;

create table if not exists locations
(
	id serial not null
		constraint locations_pk
			primary key,
	name varchar(50) not null,
	address1 varchar(40) not null,
	address2 varchar(40),
	city varchar(40) not null,
	state char(2) not null,
	zip varchar(10) not null,
	phone varchar(10) not null,
	status_id smallint default 1 not null
		constraint locations_statuses_id_fk
			references statuses
				on update restrict on delete restrict,
	created_at timestamp default CURRENT_TIMESTAMP not null,
	updated_at timestamp default CURRENT_TIMESTAMP not null
);

alter table locations owner to vimujnexvsdlyl;

create table if not exists location_user
(
	location_id integer not null
		constraint location_user_locations_id_fk
			references locations
				on update cascade on delete cascade,
	user_id integer not null
		constraint location_user_users_id_fk
			references users
				on update cascade on delete cascade
);

alter table location_user owner to vimujnexvsdlyl;

create unique index if not exists location_user_location_id_user_id_uindex
	on location_user (location_id, user_id);

create table if not exists queues
(
	id serial not null
		constraint queues_pk
			primary key,
	location_id integer not null
		constraint queue_locations_id_fk
			references locations
				on update cascade on delete cascade,
	status_id integer not null
		constraint queues_statuses_id_fk
			references statuses
				on update restrict on delete restrict,
	created_at timestamp default CURRENT_TIMESTAMP,
	updated_at timestamp default CURRENT_TIMESTAMP not null
);

alter table queues owner to vimujnexvsdlyl;

create table if not exists queue_items
(
	id serial not null
		constraint queue_items_pk
			primary key,
	queue_id integer not null
		constraint queue_items_queues_id_fk
			references queues
				on update cascade on delete cascade,
	status_id integer default 1 not null
		constraint queue_items_statuses_id_fk
			references statuses
				on update restrict on delete restrict,
	token varchar(80) not null,
	created_at timestamp default CURRENT_TIMESTAMP not null,
	updated_at timestamp default CURRENT_TIMESTAMP
);

alter table queue_items owner to vimujnexvsdlyl;

create index if not exists queue_items_queue_id_status_id_index
	on queue_items (queue_id, status_id);


