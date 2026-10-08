CREATE TABLE IF NOT EXISTS "migrations"(
  "id" integer primary key autoincrement not null,
  "migration" varchar not null,
  "batch" integer not null
);
CREATE TABLE IF NOT EXISTS "users"(
  "id" integer primary key autoincrement not null,
  "name" varchar not null,
  "email" varchar not null,
  "password" varchar not null,
  "role" varchar not null default 'user',
  "created_at" datetime,
  "updated_at" datetime
);
CREATE UNIQUE INDEX "users_email_unique" on "users"("email");
CREATE INDEX "users_role_index" on "users"("role");
CREATE TABLE IF NOT EXISTS "books"(
  "id" integer primary key autoincrement not null,
  "title" varchar not null,
  "author" varchar not null,
  "category" varchar not null,
  "published_year" integer not null,
  "description" text not null,
  "price" numeric not null,
  "status" varchar not null default 'published',
  "is_available" tinyint(1) not null default '1',
  "created_at" datetime,
  "updated_at" datetime,
  "preview_path" varchar,
  "rental_price_14_days" numeric not null default '0',
  "rental_price_30_days" numeric not null default '0',
  "rental_price_90_days" numeric not null default '0'
);
CREATE INDEX "books_author_index" on "books"("author");
CREATE INDEX "books_category_index" on "books"("category");
CREATE INDEX "books_published_year_index" on "books"("published_year");
CREATE INDEX "books_status_index" on "books"("status");
CREATE TABLE IF NOT EXISTS "orders"(
  "id" integer primary key autoincrement not null,
  "user_id" integer,
  "book_id" integer not null,
  "order_type" varchar not null,
  "rental_term_days" integer,
  "price" numeric not null,
  "starts_at" datetime not null,
  "ends_at" datetime,
  "status" varchar not null default('active'),
  "reminder_sent_at" datetime,
  "created_at" datetime,
  "updated_at" datetime,
  "guest_name" varchar,
  "guest_email" varchar,
  foreign key("book_id") references books("id") on delete restrict on update no action,
  foreign key("user_id") references "users"("id") on delete set null
);
CREATE INDEX "orders_order_type_ends_at_index" on "orders"(
  "order_type",
  "ends_at"
);
CREATE INDEX "orders_status_index" on "orders"("status");
CREATE INDEX "orders_user_id_status_index" on "orders"("user_id", "status");
CREATE TABLE IF NOT EXISTS "rental_reminders"(
  "id" integer primary key autoincrement not null,
  "user_id" integer,
  "order_id" integer not null,
  "message" text not null,
  "sent_at" datetime not null,
  "created_at" datetime,
  "updated_at" datetime,
  foreign key("order_id") references orders("id") on delete cascade on update no action,
  foreign key("user_id") references "users"("id") on delete set null
);

