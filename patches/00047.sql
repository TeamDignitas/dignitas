alter table answer add pendingEditId int not null default 0 after reason;
alter table entity add pendingEditId int not null default 0 after duplicateId;
alter table statement add pendingEditId int not null default 0 after duplicateId;

alter table history_answer add pendingEditId int not null default 0 after reason;
alter table history_entity add pendingEditId int not null default 0 after duplicateId;
alter table history_statement add pendingEditId int not null default 0 after duplicateId;
