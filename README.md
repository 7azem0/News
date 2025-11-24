<h1>READ ALL</h1>

<h2> Ensure Docker is installed. </h2>

- After getting your clone, CREATE the working environment through:<br>
<pre>
"docker-compose up -d" OR "docker compose up -d"
</pre>
NOTE : This command will run only one time After clonning the repo.
<br>
- You are free to change the yml file to fit your device.
- Commit frequently and describe any changes to the core env.
<br>
- To stop the Docker containers :
<pre>
"docker-compose stop"
</pre>
- To start them again:<br>
<pre>
"docker-compose start"</h2>
</pre>
</br>

<h2> Database Import Instructions </h2>

- Inside the project, there is a file named <b>news_dump.sql</b> containing the SQL dump.<br>
  This file contains the <b>tables + sample data</b> used by the project.

- After running the Docker environment for the first time, IMPORT the database through the following steps:

</br>

<b>1. Copy the SQL dump into the MySQL container</b><br>
Use this command (from the project root folder):
<pre>
docker cp news_dump.sql mysql_db:/tmp/news_dump.sql
</pre>

</br>

<b>2. Enter the MySQL container</b>
<pre>
docker exec -it mysql_db bash
</pre>

</br>

<b>3. Login into MySQL</b>
<pre>
mysql -u root -p
</pre>
Password (from docker-compose): <b>root</b>

</br>

<b>4. Create the database and switch to it</b>
<pre>
DROP DATABASE IF EXISTS News;
CREATE DATABASE News;
USE News;
</pre>

</br>

<b>5. Import the SQL dump</b>
<pre>
SOURCE /tmp/news_dump.sql;
</pre>

</br>

<b>6. Verification (optional)</b><br>
After importing, you can check that everything is correct:
<pre>
SHOW TABLES;
</pre>

You should see the list of tables (articles, users, categories, subscriptions, etc.)

</br>

<b>Note:</b> Repeat this process ONLY when a new SQL dump is pushed to the repository.

</br>
</br>



<h1>Responsabilies :</h1> 
</br>

<h2>1: Content Delivery & Reading Experience</h2>

- Focus: Everything related to articles, magazines, languages, and how users read/view them.

- Main Responsibilities

- Handle loading/displaying newspapers & magazines.

- Support original print view and web-optimised text view.

- Implement translation (up to 16 languages).

- Implement listening mode (text-to-speech for articles).

- Display article content cleanly (titles, images, paragraphs, etc.).

- Provide save-for-later functionality.

- Handle article sharing to social platforms.

- Display interactive graphics & data visualizations inside articles.

- Support viewing full issues (downloadable magazines/newspapers).

</br>
</br>

<h2>2: Discovery, Search & Personalization</h2>

- Focus: How users find content, how content is organized, and how content is recommended.

- Main Responsibilities

- Categorize content into sections (business, tech, arts, breaking news…).

- Build a search system (by language, journalist, topic, keywords, etc.).

- Implement recommendations such as:

- “For You” page

- Trending articles

- Suggested topics & personalized content

- Build the Home sections:

- Morning Briefing

- Live Briefings

- Widgets for quick news glance

- Manage tags, metadata, and filtering logic.

- Implement user “favorites” system for saved articles.

</br>
</br>

<h2>3: User Accounts & Subscription System</h2>

- Focus: Everything involving user management, subscription plans, and permissions.

- Main Responsibilities

- User registration, login, logout.

- Manage subscription plans:

- Premium (unlimited access)

- Single-issue purchase

- Single-title subscription

- Control what each user can access (free vs premium).

- Subscription renewal management:

- Auto-renew on/off

- Payment status handling

- User profile page:

- Account info

- Subscriptions overview

- Saved articles

- Sync user data across devices (saved articles, preferences).

</br>
</br>

<h2>4: Backend Infrastructure, Database & API Integration</h2>

- Focus: The foundation — servers, databases, APIs, performance, and content sources.

- Main Responsibilities

- Design and maintain the database (articles, categories, users, subscriptions).

- Build backend APIs for:

   - Articles

   - Categories

   - Search

   - User management

   - Subscription validation

- Integrate external content/news sources if required.

- Handle downloading & storing newspaper/magazine issues.

- Handle comments system (dynamic commenting).

- Manage performance optimization (caching, CDNs, fast loading).

- Manage push notifications or alerts (optional).

</h2>
