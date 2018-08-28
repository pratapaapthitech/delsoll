

CREATE TABLE campaigns (
  id integer,
  user_id integer,
  fund_raiser_id integer,
  category_id integer,
  title varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  slug varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  short_description varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  description longtext COLLATE utf8mb4_unicode_ci,
  campaign_owner_commission decimal(8,2) DEFAULT NULL,
  goal decimal(8,2) DEFAULT NULL,
  min_amount decimal(8,2) DEFAULT NULL,
  max_amount decimal(8,2) DEFAULT NULL,
  recommended_amount decimal(8,2) DEFAULT NULL,
  amount_prefilled varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  end_method varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  views integer(11) DEFAULT NULL,
  video varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  feature_image varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  status tinyinteger(4) DEFAULT '1',
  country_id mediuminteger(9) DEFAULT NULL,
  address varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  is_funded tinyinteger(4) DEFAULT NULL,
  is_staff_picks tinyinteger(4) DEFAULT NULL,
  start_date date DEFAULT NULL,
  end_date date DEFAULT NULL,
  created_at timestamp NULL DEFAULT NULL,
  updated_at timestamp NULL DEFAULT NULL,
  PRIMARY KEY (id)
);



CREATE TABLE categories (
  id integer,
  category_name varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  category_slug varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  image varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  created_at timestamp NULL DEFAULT NULL,
  updated_at timestamp NULL DEFAULT NULL,
  PRIMARY KEY (id)
);


CREATE TABLE countries (
  id integer,
  capital varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  citizenship varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  country_code varchar(3) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  currency varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  currency_code varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  currency_sub_unit varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  currency_symbol varchar(3) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  currency_decimals integer(11) DEFAULT NULL,
  full_name varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  iso_3166_2 varchar(2) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  iso_3166_3 varchar(3) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  name varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  region_code varchar(3) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  sub_region_code varchar(3) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  eea tinyinteger(1) NOT NULL DEFAULT '0',
  calling_code varchar(3) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  flag varchar(6) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (id)
);


CREATE TABLE faqs (
  id integer,
  user_id integer,
  campaign_id integer,
  title varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  description text COLLATE utf8mb4_unicode_ci,
  created_at timestamp NULL DEFAULT NULL,
  updated_at timestamp NULL DEFAULT NULL,
  PRIMARY KEY (id)
);


CREATE TABLE fund_raisers (
  id integer,
  user_id integer,
  fund_category_id integer(11) DEFAULT NULL,
  fund_title text,
  fund_sub_title text,
  fund_goal_ammount varchar(20) DEFAULT NULL,
  fund_tax_exempt tinyinteger(4) DEFAULT '0' COMMENT '0-No tax 1-Add tax',
  fund_begin_type tinyinteger(4) DEFAULT '1' COMMENT '1-Immediate 2-Select date',
  fund_begin_date date DEFAULT NULL,
  fund_logo_image text,
  fund_banner_image text,
  fund_own_image text,
  created_at datetime DEFAULT NULL,
  updated_at datetime DEFAULT NULL,
  fund_status tinyinteger(4) DEFAULT '1',
  PRIMARY KEY (id)
);


CREATE TABLE fund_raisers_descriptions (
  fd_id integer,
  fund_raiser_id integer,
  fd_title text,
  fd_description longtext,
  created_at datetime DEFAULT NULL,
  updated_at datetime DEFAULT NULL,
  fd_status tinyinteger(4) DEFAULT '1',
  PRIMARY KEY (fd_id)
);


CREATE TABLE fund_raisers_images (
  id integer(11) NOT NULL AUTO_INCREMENT,
  fund_raiser_id integer(11) DEFAULT NULL,
  fi_image text,
  created_at datetime DEFAULT NULL,
  updated_at datetime DEFAULT NULL,
  fi_status tinyinteger(4) DEFAULT '1',
  PRIMARY KEY (id)
); 


CREATE TABLE migrations (
  id integer,
  migration varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  batch integer(11) NOT NULL,
  PRIMARY KEY (id)
);


CREATE TABLE options (
  id integer,
  option_key varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  option_value text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (id)
);



CREATE TABLE password_resets (
  email varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  token varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  created_at timestamp NULL DEFAULT NULL,
  KEY password_resets_email_index (email),
  KEY password_resets_token_index (token)
);



CREATE TABLE payments (
  id integer,
  name varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  email varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  fund_raiser_id integer,
  campaign_id integer,
  user_id integer,
  reward_id integer,
  amount decimal(8,2) DEFAULT NULL,
  payment_method varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  status enum('initial','pending','success','failed','declined','dispute') COLLATE utf8mb4_unicode_ci DEFAULT 'initial',
  currency varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  token_id varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  card_last4 varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  card_id varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  card_brand varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  card_country varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  card_exp_month varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  card_exp_year varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  client_ip varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  charge_id_or_token varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  payer_email varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  description varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  local_transaction_id varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  payment_created integer(11) DEFAULT NULL,
  contributor_name_display varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  bank_swift_code varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  account_number varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  branch_name varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  branch_address varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  account_name varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  iban varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  created_at timestamp NULL DEFAULT NULL,
  updated_at timestamp NULL DEFAULT NULL,
  PRIMARY KEY (id)
);



CREATE TABLE posts (
  id integer,
  user_id integer,
  title varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  slug varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  post_content longtext COLLATE utf8mb4_unicode_ci,
  feature_image varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  type enum('post','page') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  status tinyinteger(4) DEFAULT NULL,
  show_in_header_menu tinyinteger(4) DEFAULT NULL,
  show_in_footer_menu tinyinteger(4) DEFAULT NULL,
  created_at timestamp NULL DEFAULT NULL,
  updated_at timestamp NULL DEFAULT NULL,
  PRIMARY KEY (id)
);



CREATE TABLE rewards (
  id integer,
  user_id integer,
  campaign_id integer,
  amount decimal(8,2) DEFAULT NULL,
  description text COLLATE utf8mb4_unicode_ci,
  estimated_delivery varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  quantity integer(11) DEFAULT NULL,
  equity_share_percent decimal(8,2) DEFAULT NULL,
  PRIMARY KEY (id)
);



CREATE TABLE sessions (
  id varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  user_id integer,
  ip_address varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  user_agent text COLLATE utf8mb4_unicode_ci,
  payload text COLLATE utf8mb4_unicode_ci NOT NULL,
  last_activity integer,
  UNIQUE KEY sessions_id_unique (id)
);



CREATE TABLE social_accounts (
  id integer,
  user_id integer,
  provider_user_id varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  provider varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  created_at timestamp NULL DEFAULT NULL,
  updated_at timestamp NULL DEFAULT NULL,
  PRIMARY KEY (id)
);



CREATE TABLE updates (
  id integer,
  user_id integer,
  campaign_id integer,
  title varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  description text COLLATE utf8mb4_unicode_ci NOT NULL,
  created_at timestamp NULL DEFAULT NULL,
  updated_at timestamp NULL DEFAULT NULL,
  PRIMARY KEY (id)
);



CREATE TABLE user_stripes (
  stripe_id integer,
  user_id integer,
  stripe_user_id text,
  token_type varchar(200) DEFAULT NULL,
  stripe_publishable_key text,
  scope varchar(100) DEFAULT NULL,
  livemode varchar(100) DEFAULT NULL,
  refresh_token text,
  access_token text,
  created_at datetime DEFAULT NULL,
  updated_at datetime DEFAULT NULL,
  status tinyinteger(4) DEFAULT '1',
  PRIMARY KEY (stripe_id)
);



CREATE TABLE users (
  id integer,
  name varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  email varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  password varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  country_id integer,
  gender enum('male','female') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  address varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  website varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  phone varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  photo varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  user_type enum('user','admin') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  active_status tinyinteger(4) DEFAULT NULL,
  remember_token varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  created_at timestamp NULL DEFAULT NULL,
  updated_at timestamp NULL DEFAULT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY users_email_unique (email)
);



CREATE TABLE withdrawal_preferences (
  id integer,
  user_id integer,
  default_withdrawal_account varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  paypal_email varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  bank_account_holders_name varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  bank_account_number varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  swift_code varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  bank_name_full varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  bank_branch_name varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  bank_branch_city varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  bank_branch_address varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  country_id varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  created_at timestamp NULL DEFAULT NULL,
  updated_at timestamp NULL DEFAULT NULL,
  PRIMARY KEY (id)
);



CREATE TABLE withdrawal_requests (
  id integer,
  user_id integer,
  campaign_id integer,
  total_amount double(10,2) NOT NULL,
  withdrawal_amount double(10,2) NOT NULL,
  platform_owner_commission double(10,2) NOT NULL,
  withdrawal_account varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  paypal_email varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  bank_account_holders_name varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  bank_account_number varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  swift_code varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  bank_name_full varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  bank_branch_name varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  bank_branch_city varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  bank_branch_address varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  country_id varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  status varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  created_at timestamp NULL DEFAULT NULL,
  updated_at timestamp NULL DEFAULT NULL,
  PRIMARY KEY (id)
);

