-- user

create table user_ext (
  id int not null auto_increment,
  userId int not null default 0,
  reputation int not null default 0,
  lastSeen int not null default 0,
  numPendingEdits int not null default 0,

  primary key (id),
  unique key (userId)
);

insert into user_ext
select null, id, reputation, lastSeen, numPendingEdits from user;

alter table user
  drop reputation,
  drop lastSeen,
  drop numPendingEdits;

alter table revision_user
  drop reputation,
  drop lastSeen,
  drop numPendingEdits;

-- statement

create table statement_ext (
  id int not null auto_increment,
  statementId int not null default 0,
  score int not null default 0,

  primary key (id),
  unique key (statementId)
);

insert into statement_ext
select null, id, score from statement;

alter table statement drop score;

alter table revision_statement drop score;

-- answer

create table answer_ext (
  id int not null auto_increment,
  answerId int not null default 0,
  score int not null default 0,

  primary key (id),
  unique key (answerId)
);

insert into answer_ext
select null, id, score from answer;

alter table answer drop score;

alter table revision_answer drop score;

-- comment

create table comment_ext (
  id int not null auto_increment,
  commentId int not null default 0,
  score int not null default 0,

  primary key (id),
  unique key (commentId)
);

insert into comment_ext
select null, id, score from comment;

alter table comment drop score;

alter table revision_comment drop score;
