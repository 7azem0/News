```mermaid
sequenceDiagram
    participant User
    participant Router
    participant UserController
    participant ArticleController
    participant SubscriptionController
    participant UserModel
    participant ArticleModel
    participant Database
    participant LibreTranslate
    participant NewsAPI

    %% ===== USER REGISTRATION =====
    User->>+Router: POST /register
    Router->>+UserController: register()
    UserController->>+UserModel: checkEmailExists(email)
    UserModel->>+Database: SELECT id FROM users WHERE email=?
    Database-->>-UserModel: Email status
    alt Email already exists
        UserModel-->>-UserController: Email taken
        UserController-->>-Router: Show error
        Router-->>-User: Registration with email error
    else Email available
        UserController->>+UserModel: checkUsernameExists(username)
        UserModel->>+Database: SELECT id FROM users WHERE username=?
        Database-->>-UserModel: Username status
        alt Username already exists
            UserModel-->>-UserController: Username taken
            UserController-->>-Router: Show error
            Router-->>-User: Registration with username error
        else Username available
            UserController->>+UserModel: create(username, email, hashed_password)
            UserModel->>+Database: INSERT INTO users (username, email, password)
            Database-->>-UserModel: User ID
            UserModel-->>-UserController: Success
            UserController->>+SubscriptionController: createTrial(user_id)
            SubscriptionController->>+Database: INSERT INTO subscriptions (user_id, plan_id, expires_at)
            Database-->>-SubscriptionController: Trial created
            SubscriptionController-->>-UserController: Trial setup complete
            UserController-->>-Router: Redirect to login
            Router-->>-User: Show login page
        end
    end

    %% ===== USER LOGIN =====
    User->>+Router: POST /login
    Router->>+UserController: login()
    UserController->>+UserModel: validate(email, password)
    UserModel->>+Database: SELECT * FROM users WHERE email=?
    Database-->>-UserModel: User data
    alt User found
        UserModel->>+UserModel: verifyPassword(password, hashed_password)
        alt Password correct
            UserModel-->>-UserController: User object
            UserController->>+UserModel: updateLastLogin(user_id)
            UserModel->>+Database: UPDATE users SET last_login = NOW() WHERE id = ?
            Database-->>-UserModel: Login time updated
            UserModel-->>-UserController: Login time saved
            UserController-->>-Router: Set session (user_id, username, is_admin)
            Router-->>-User: Show dashboard
        else Password incorrect
            UserModel-->>-UserController: Invalid password
            UserController-->>-Router: Show error
            Router-->>-User: Login with password error
        end
    else User not found
        UserModel-->>-UserController: User not found
        UserController-->>-Router: Show error
        Router-->>-User: Login with user not found error
    end

    %% ===== HOME PAGE =====
    User->>+Router: GET /
    Router->>+ArticleController: home()
    ArticleController->>+ArticleModel: getFeaturedArticles()
    ArticleModel->>+Database: SELECT * FROM articles WHERE is_featured = 1 AND status = published ORDER BY created_at DESC LIMIT 5
    Database-->>-ArticleModel: Featured articles
    ArticleModel-->>-ArticleController: Featured list
    ArticleController->>+ArticleModel: getRecentArticles()
    ArticleModel->>+Database: SELECT * FROM articles WHERE status = published ORDER BY created_at DESC LIMIT 10
    Database-->>-ArticleModel: Recent articles
    ArticleModel-->>-ArticleController: Recent list
    ArticleController->>+ArticleModel: getCategories()
    ArticleModel->>+Database: SELECT DISTINCT category FROM articles WHERE category IS NOT NULL
    Database-->>-ArticleModel: Categories list
    ArticleModel-->>-ArticleController: Categories
    ArticleController-->>-Router: Render home page
    Router-->>-User: Show home page with featured and recent articles

    %% ===== ARTICLE BROWSING =====
    User->>+Router: GET /articles
    Router->>+ArticleController: index()
    ArticleController->>+ArticleModel: getAllPublished()
    ArticleModel->>+Database: SELECT * FROM articles WHERE status = published ORDER BY created_at DESC
    Database-->>-ArticleModel: Articles list
    ArticleModel-->>-ArticleController: Articles data
    ArticleController->>+ArticleModel: getCategories()
    ArticleModel-->>-ArticleController: Categories
    ArticleController-->>-Router: Render articles list
    Router-->>-User: Show all articles with filters

    %% ===== ARTICLE VIEWING =====
    User->>+Router: GET /article/123
    Router->>+ArticleController: view(123)
    ArticleController->>+ArticleModel: getById(123)
    ArticleModel->>+Database: SELECT * FROM articles WHERE id = 123 AND status = published
    Database-->>-ArticleModel: Article data
    ArticleModel-->>-ArticleController: Article object
    ArticleController->>+ArticleModel: incrementViews(123)
    ArticleModel->>+Database: UPDATE articles SET views = views + 1 WHERE id = 123
    Database-->>-ArticleModel: Views incremented
    ArticleModel-->>-ArticleController: Success
    ArticleController->>+ArticleModel: getComments(123)
    ArticleModel->>+Database: SELECT c.*, u.username FROM comments c JOIN users u ON c.user_id = u.id WHERE c.article_id = 123 AND c.status = approved ORDER BY c.created_at DESC
    Database-->>-ArticleModel: Comments list
    ArticleModel-->>-ArticleController: Comments data
    ArticleController->>+ArticleModel: getLikeCount(123)
    ArticleModel->>+Database: SELECT COUNT(*) as like_count FROM article_likes WHERE article_id = 123
    Database-->>-ArticleModel: Like count
    ArticleModel-->>-ArticleController: Like data
    ArticleController-->>-Router: Render article page
    Router-->>-User: Show article with comments and interactions

    %% ===== ARTICLE TRANSLATION =====
    User->>+Router: GET /article/123/translate?lang=es
    Router->>+ArticleController: translate(123, es)
    ArticleController->>+ArticleModel: getById(123)
    ArticleModel-->>-ArticleController: Article data
    ArticleController->>+ArticleModel: getTranslation(123, es)
    ArticleModel->>+Database: SELECT translated_text FROM translations WHERE article_id = 123 AND language = es
    Database-->>-ArticleModel: Translation data
    alt Translation exists
        ArticleModel-->>-ArticleController: Cached translation
        ArticleController-->>-Router: Render translated article
        Router-->>-User: Show translated article
    else No translation found
        ArticleController->>+LibreTranslate: translate(article_content, es)
        LibreTranslate-->>-ArticleController: Translated text
        ArticleController->>+ArticleModel: saveTranslation(123, es, translated_text)
        ArticleModel->>+Database: INSERT INTO translations (article_id, language, translated_text)
        Database-->>-ArticleModel: Translation saved
        ArticleModel-->>-ArticleController: Success
        ArticleController-->>-Router: Render translated article
        Router-->>-User: Show translated article
    end

    %% ===== ARTICLE LIKING =====
    User->>+Router: POST /article/123/like
    Router->>+ArticleController: like(123)
    ArticleController->>+ArticleModel: checkIfLiked(123, user_id)
    ArticleModel->>+Database: SELECT id FROM article_likes WHERE article_id = 123 AND user_id = ?
    Database-->>-ArticleModel: Like status
    alt Not liked yet
        ArticleController->>+ArticleModel: addLike(123, user_id)
        ArticleModel->>+Database: INSERT INTO article_likes (article_id, user_id)
        Database-->>-ArticleModel: Like added
        ArticleModel-->>-ArticleController: Success
        ArticleController->>+ArticleModel: getLikeCount(123)
        ArticleModel-->>-ArticleController: New like count
        ArticleController-->>-Router: JSON response (liked, count)
    else Already liked
        ArticleController->>+ArticleModel: removeLike(123, user_id)
        ArticleModel->>+Database: DELETE FROM article_likes WHERE article_id = 123 AND user_id = ?
        Database-->>-ArticleModel: Like removed
        ArticleModel-->>-ArticleController: Success
        ArticleController->>+ArticleModel: getLikeCount(123)
        ArticleModel-->>-ArticleController: New like count
        ArticleController-->>-Router: JSON response (unliked, count)
    end
    Router-->>-User: Update like button

    %% ===== ARTICLE SAVING =====
    User->>+Router: POST /article/123/save
    Router->>+ArticleController: saveArticle(123)
    ArticleController->>+ArticleModel: checkIfSaved(123, user_id)
    ArticleModel->>+Database: SELECT id FROM saved_articles WHERE article_id = 123 AND user_id = ?
    Database-->>-ArticleModel: Save status
    alt Not saved yet
        ArticleController->>+ArticleModel: saveArticle(123, user_id)
        ArticleModel->>+Database: INSERT INTO saved_articles (article_id, user_id)
        Database-->>-ArticleModel: Article saved
        ArticleModel-->>-ArticleController: Success
        ArticleController-->>-Router: JSON response (saved)
    else Already saved
        ArticleController->>+ArticleModel: unsaveArticle(123, user_id)
        ArticleModel->>+Database: DELETE FROM saved_articles WHERE article_id = 123 AND user_id = ?
        Database-->>-ArticleModel: Article unsaved
        ArticleModel-->>-ArticleController: Success
        ArticleController-->>-Router: JSON response (unsaved)
    end
    Router-->>-User: Update save button

    %% ===== COMMENTING =====
    User->>+Router: POST /article/123/comment
    Router->>+ArticleController: addComment(123)
    ArticleController->>+ArticleModel: saveComment(123, user_id, comment_text)
    ArticleModel->>+Database: INSERT INTO comments (article_id, user_id, content, status)
    Database-->>-ArticleModel: Comment ID
    ArticleModel-->>-ArticleController: Comment data
    ArticleController->>+UserModel: getUsername(user_id)
    UserModel->>+Database: SELECT username FROM users WHERE id = ?
    Database-->>-UserModel: Username
    UserModel-->>-ArticleController: Username
    ArticleController-->>-Router: JSON response (comment_id, username, content, created_at)
    Router-->>-User: Show new comment

    %% ===== USER PROFILE =====
    User->>+Router: GET /profile
    Router->>+UserController: profile()
    UserController->>+UserModel: getProfile(user_id)
    UserModel->>+Database: SELECT u.*, s.plan, s.expires_at FROM users u LEFT JOIN subscriptions s ON u.id = s.user_id WHERE u.id = ?
    Database-->>-UserModel: User profile data
    UserModel-->>-UserController: Profile object
    UserController->>+UserModel: getSavedArticles(user_id)
    UserModel->>+Database: SELECT a.* FROM articles a JOIN saved_articles sa ON a.id = sa.article_id WHERE sa.user_id = ?
    Database-->>-UserModel: Saved articles
    UserModel-->>-UserController: Saved articles list
    UserController->>+UserModel: getLikedArticles(user_id)
    UserModel->>+Database: SELECT a.* FROM articles a JOIN article_likes al ON a.id = al.article_id WHERE al.user_id = ?
    Database-->>-UserModel: Liked articles
    UserModel-->>-UserController: Liked articles list
    UserController-->>-Router: Render profile page
    Router-->>-User: Show profile with saved and liked articles

    %% ===== SUBSCRIPTION PLANS =====
    User->>+Router: GET /plans
    Router->>+SubscriptionController: listPlans()
    SubscriptionController->>+Database: SELECT * FROM plans ORDER BY level, price
    Database-->>-SubscriptionController: Plans list
    SubscriptionController->>+UserModel: getCurrentSubscription(user_id)
    UserModel->>+Database: SELECT s.*, p.name as plan_name FROM subscriptions s JOIN plans p ON s.plan_id = p.id WHERE s.user_id = ? AND s.expires_at > NOW()
    Database-->>-UserModel: Current subscription
    UserModel-->>-SubscriptionController: Subscription data
    SubscriptionController-->>-Router: Render plans page
    Router-->>-User: Show available plans with current subscription

    %% ===== SUBSCRIBE TO PLAN =====
    User->>+Router: POST /subscribe/plan/123
    Router->>+SubscriptionController: subscribe(123)
    SubscriptionController->>+UserModel: checkActiveSubscription(user_id)
    UserModel->>+Database: SELECT id FROM subscriptions WHERE user_id = ? AND expires_at > NOW()
    Database-->>-UserModel: Active subscription status
    alt No active subscription
        SubscriptionController->>+Database: SELECT duration_days, price FROM plans WHERE id = 123
        Database-->>-SubscriptionController: Plan details
        SubscriptionController->>+Database: INSERT INTO subscriptions (user_id, plan_id, expires_at, auto_renew) VALUES (?, ?, DATE_ADD(NOW(), INTERVAL duration_days DAY), 1)
        Database-->>-SubscriptionController: Subscription created
        SubscriptionController-->>-Router: Redirect to profile
        Router-->>-User: Show subscription success
    else Has active subscription
        SubscriptionController->>+Database: UPDATE subscriptions SET plan_id = 123, expires_at = DATE_ADD(NOW(), INTERVAL duration_days DAY) WHERE user_id = ?
        Database-->>-SubscriptionController: Subscription updated
        SubscriptionController-->>-Router: Redirect to profile
        Router-->>-User: Show subscription updated
    end

    %% ===== SEARCH =====
    User->>+Router: GET /search?q=technology
    Router->>+ArticleController: search(technology)
    ArticleController->>+ArticleModel: search(technology)
    ArticleModel->>+Database: SELECT * FROM articles WHERE (title LIKE ? OR content LIKE ? OR tags LIKE ?) AND status = published ORDER BY created_at DESC
    Database-->>-ArticleModel: Search results
    ArticleModel-->>-ArticleController: Results list
    ArticleController-->>-Router: Render search results
    Router-->>-User: Show search results

    %% ===== CATEGORY BROWSING =====
    User->>+Router: GET /category/technology
    Router->>+ArticleController: getByCategory(technology)
    ArticleController->>+ArticleModel: getByCategory(technology)
    ArticleModel->>+Database: SELECT * FROM articles WHERE category = ? AND status = published ORDER BY created_at DESC
    Database-->>-ArticleModel: Category articles
    ArticleModel-->>-ArticleController: Articles list
    ArticleController-->>-Router: Render category page
    Router-->>-User: Show category articles

    %% ===== EXTERNAL NEWS =====
    User->>+Router: GET /news
    Router->>+ArticleController: fetchNews()
    ArticleController->>+NewsAPI: getTopHeadlines(us, technology)
    NewsAPI-->>-ArticleController: News articles
    ArticleController->>+ArticleModel: saveNews(articles)
    loop for each article
        ArticleModel->>+Database: INSERT INTO articles (title, content, author, category, language, thumbnail, status)
        Database-->>-ArticleModel: Article saved
    end
    ArticleModel-->>-ArticleController: All saved
    ArticleController-->>-Router: Render news page
    Router-->>-User: Show external news

    %% ===== USER SETTINGS =====
    User->>+Router: GET /settings
    Router->>+UserController: settings()
    UserController->>+UserModel: getSettings(user_id)
    UserModel->>+Database: SELECT * FROM user_preferences WHERE user_id = ?
    Database-->>-UserModel: User settings
    UserModel-->>-UserController: Settings data
    UserController-->>-Router: Render settings page
    Router-->>-User: Show settings

    User->>+Router: POST /settings/update
    Router->>+UserController: updateSettings()
    UserController->>+UserModel: updateSettings(user_id, settings_data)
    UserModel->>+Database: UPDATE user_preferences SET ... WHERE user_id = ?
    Database-->>-UserModel: Settings updated
    UserModel-->>-UserController: Success
    UserController-->>-Router: Redirect to settings
    Router-->>-User: Show success message

    %% ===== LOGOUT =====
    User->>+Router: GET /logout
    Router->>+UserController: logout()
    UserController->>+UserModel: updateLastLogout(user_id)
    UserModel->>+Database: UPDATE users SET last_logout = NOW() WHERE id = ?
    Database-->>-UserModel: Logout time saved
    UserModel-->>-UserController: Success
    UserController-->>-Router: Destroy session
    Router-->>-User: Redirect to home
```
