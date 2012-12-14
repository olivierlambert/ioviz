----
-- This file is a part of IoViz.
--
-- IoViz is free software: you can redistribute it and/or modify
-- it under the terms of the GNU General Public License as published by
-- the Free Software Foundation, either version 3 of the License, or
-- (at your option) any later version.
--
-- IoViz is distributed in the hope that it will be useful,
-- but WITHOUT ANY WARRANTY; without even the implied warranty of
-- MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
-- GNU General Public License for more details.
--
-- You should have received a copy of the GNU General Public License
-- along with IoViz. If not, see <http://www.gnu.org/licenses/>.
--
-- @author Julien Fontanet <julien.fontanet@isonoe.net>
-- @license http://www.gnu.org/licenses/gpl-3.0-standalone.html GPLv3
--
-- @package IoViz
----

--
--
CREATE TABLE "benchmark"
(
	"id" SERIAL,
	"name" TEXT NOT NULL,
	"comment" TEXT NOT NULL DEFAULT '',

	PRIMARY KEY ("id")
);

--
--
CREATE TYPE RGRP_TYPE AS ENUM ('NETWORK', 'MACHINE', 'DEVICE');

-- A result group.
--
CREATE TABLE "rgrp"
(
	"id" SERIAL,
	"type" RGRP_TYPE NOT NULL,
	"parent_id" INTEGER DEFAULT NULL,
	"name" TEXT NOT NULL,
	"comment" TEXT NOT NULL DEFAULT '',

	PRIMARY KEY ("id"),
	FOREIGN KEY ("parent_id") REFERENCES "rgrp" ("id")
);

--
--
CREATE TABLE "result"
(
	"id" SERIAL,
	"benchmark_id" INTEGER NOT NULL,
	"group_id" INTEGER DEFAULT NULL,
	"date" TIMESTAMP NOT NULL DEFAULT NOW(),
	"data" TEXT NOT NULL,
	"comment" TEXT NOT NULL DEFAULT '',

	PRIMARY KEY ("id"),
	FOREIGN KEY ("benchmark_id") REFERENCES "benchmark" ("id"),
	FOREIGN KEY ("group_id") REFERENCES "rgrp" ("id")
);

--
--
CREATE TABLE "user"
(
	"id" SERIAL,
	"name" TEXT NOT NULL,
	"password" TEXT NOT NULL,
	"email" TEXT NOT NULL,

	PRIMARY KEY ("id"),
	UNIQUE ("name"),
	UNIQUE ("email")
);


-- An user group.
--
CREATE TABLE "ugrp"
(
	"id" SERIAL,
	"name" TEXT NOT NULL,
	"comment" TEXT NOT NULL DEFAULT '',

	PRIMARY KEY ("id"),
	UNIQUE ("name")
);

--
--
CREATE TABLE "user_ugrp"
(
	"user_id" INTEGER,
	"ugrp_id" INTEGER,

	PRIMARY KEY ("user_id", "ugrp_id"),
	FOREIGN KEY ("user_id") REFERENCES "user" ("id"),
	FOREIGN KEY ("ugrp_id") REFERENCES "ugrp" ("id")
);

--
--
CREATE TYPE ACL_PERMISSION AS ENUM ('NONE', 'READ', 'WRITE', 'ADMIN');

--
--
CREATE TABLE "acl"
(
	"ugrp_id" INTEGER,
	"rgrp_id" INTEGER,
	"permission" ACL_PERMISSION NOT NULL,

	PRIMARY KEY ("ugrp_id", "rgrp_id"),
	FOREIGN KEY ("ugrp_id") REFERENCES "ugrp" ("id"),
	FOREIGN KEY ("rgrp_id") REFERENCES "rgrp" ("id")
);
