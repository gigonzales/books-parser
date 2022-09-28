create table authors
(
    id   serial
        constraint authors_pk
            primary key,
    name varchar not null
);

alter table authors
    owner to postgres;

create table books
(
    id        serial
        constraint books_pk
            primary key,
    name      varchar(64),
    author_id integer
        constraint books_authors_id_fk
            references authors
);

alter table books
    owner to postgres;

create unique index books_id_uindex
    on books (id);

create unique index authors_id_uindex
    on authors (id);

create unique index authors_name_uindex
    on authors (name);


