<h1>READ ALL</h1>
<h2> Ensure Docker is installed. </h2>

- After getting your clone, CREATE the working environment through:<br>
"docker-compose up -d" OR "docker compose up -d"<br>
NOTE : This command will run only one time After clonning the repo.<br>
- You are free to change the yml file to fit your device.
- Commit frequently and describe any changes to the core env.
- When creating a new Table in the database, provide an ERD in the group chat.
- To stop the Docker containers :<br>
  "docker-compose stop"
  and to start them again : "docker-compose start"
</h2>

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
