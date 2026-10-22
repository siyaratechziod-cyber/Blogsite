<?php

namespace Database\Seeders;

use App\Models\Bookmark;
use App\Models\Category;
use App\Models\Comment;
use App\Models\ContactMessage;
use App\Models\Like;
use App\Models\NewsletterSubscriber;
use App\Models\Post;
use App\Models\Tag;
use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class BlogHubSeeder extends Seeder
{
    public function run(): void
    {
        // 1. ADMIN USER
        $admin = User::firstOrCreate(
            ['email' => 'admin@bloghub.test'],
            [
                'name' => 'Alexander Vance',
                'username' => 'alexandervance',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'specialty' => 'Editor-in-Chief & Distributed Systems Architect',
                'bio' => 'Technologist, writer, and architecture consultant with 15+ years guiding engineering teams from seed stage to hyper-growth.',
                'profile_image' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=400&q=80',
                'cover_image' => 'https://images.unsplash.com/photo-1519389950473-47ba0277781c?auto=format&fit=crop&w=1200&q=80',
                'social_links' => ['twitter' => 'https://x.com/alexandervance', 'github' => 'https://github.com/alexandervance', 'linkedin' => 'https://linkedin.com/in/alexandervance'],
            ]
        );

        // 2. AUTHORS (8 authors)
        $authorsData = [
            [
                'name' => 'Sophia Chen',
                'email' => 'sophia.chen@bloghub.test',
                'username' => 'sophiachen',
                'role' => 'author',
                'specialty' => 'Staff AI Researcher & ML Systems Lead',
                'bio' => 'Investigating transformer architectures, multi-modal reasoning models, and production ML pipelines. Previously at DeepMind partner labs.',
                'profile_image' => 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&w=400&q=80',
                'cover_image' => 'https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?auto=format&fit=crop&w=1200&q=80',
                'social_links' => ['twitter' => 'https://x.com/sophiachen', 'github' => 'https://github.com/sophiachen'],
            ],
            [
                'name' => 'Marcus Brooks',
                'email' => 'marcus.brooks@bloghub.test',
                'username' => 'marcusbrooks',
                'role' => 'author',
                'specialty' => 'Principal Full-Stack & PHP/Laravel Core Advocate',
                'bio' => 'High-load architecture engineer and open-source contributor. Obsessed with clean abstractions, cache locality, and Eloquent internals.',
                'profile_image' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=400&q=80',
                'cover_image' => 'https://images.unsplash.com/photo-1526374965328-7f61d4dc18c5?auto=format&fit=crop&w=1200&q=80',
                'social_links' => ['twitter' => 'https://x.com/marcusbrooks', 'github' => 'https://github.com/marcusbrooks'],
            ],
            [
                'name' => 'Elena Rostova',
                'email' => 'elena.rostova@bloghub.test',
                'username' => 'elenarostova',
                'role' => 'author',
                'specialty' => 'Lead UI/UX Architect & Design Systems Engineer',
                'bio' => 'Bridging visual ergonomics and accessible frontend component systems. Believer in semantic markup and pixel perfection.',
                'profile_image' => 'https://images.unsplash.com/photo-1580489944761-15a19d654956?auto=format&fit=crop&w=400&q=80',
                'cover_image' => 'https://images.unsplash.com/photo-1507238691740-187a5b1d37b8?auto=format&fit=crop&w=1200&q=80',
                'social_links' => ['dribbble' => 'https://dribbble.com/elenarostova', 'twitter' => 'https://x.com/elenarostova'],
            ],
            [
                'name' => 'David Kim',
                'email' => 'david.kim@bloghub.test',
                'username' => 'davidkim',
                'role' => 'author',
                'specialty' => 'Site Reliability Lead & Cloud Native Architect',
                'bio' => 'Managing Kubernetes clusters across multi-region hybrid clouds. Writes about disaster recovery, GitOps, and observability.',
                'profile_image' => 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&w=400&q=80',
                'cover_image' => 'https://images.unsplash.com/photo-1451187580459-43490279c0fa?auto=format&fit=crop&w=1200&q=80',
                'social_links' => ['github' => 'https://github.com/davidkim', 'linkedin' => 'https://linkedin.com/in/davidkim'],
            ],
            [
                'name' => 'Priya Patel',
                'email' => 'priya.patel@bloghub.test',
                'username' => 'priyapatel',
                'role' => 'author',
                'specialty' => 'Information Security Lead & Ethical Hacker',
                'bio' => 'OSCP certified red teamer focusing on API vulnerabilities, zero-trust enterprise security, and cryptographic verification.',
                'profile_image' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=400&q=80',
                'cover_image' => 'https://images.unsplash.com/photo-1563986768609-322da13575f3?auto=format&fit=crop&w=1200&q=80',
                'social_links' => ['twitter' => 'https://x.com/priyasec', 'github' => 'https://github.com/priyapatel'],
            ],
            [
                'name' => 'Liam O Connor',
                'email' => 'liam.oconnor@bloghub.test',
                'username' => 'liamoconnor',
                'role' => 'author',
                'specialty' => 'Principal Mobile Engineer',
                'bio' => 'Crafting native iOS and Android experiences for 10M+ DAU platforms. Specializes in offline sync and render pipeline optimization.',
                'profile_image' => 'https://images.unsplash.com/photo-1519085360753-af0119f7cbe7?auto=format&fit=crop&w=400&q=80',
                'cover_image' => 'https://images.unsplash.com/photo-1512941937669-90a1b58e7e9c?auto=format&fit=crop&w=1200&q=80',
                'social_links' => ['github' => 'https://github.com/liamoconnor'],
            ],
            [
                'name' => 'Maya Lin',
                'email' => 'maya.lin@bloghub.test',
                'username' => 'mayalin',
                'role' => 'author',
                'specialty' => 'Staff Data Scientist & Distributed Analytics Specialist',
                'bio' => 'Building petabyte-scale stream processing engines with Kafka, DuckDB, and ClickHouse. Big fan of reproducible benchmarks.',
                'profile_image' => 'https://images.unsplash.com/photo-1544005313-94ddf0286df2?auto=format&fit=crop&w=400&q=80',
                'cover_image' => 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?auto=format&fit=crop&w=1200&q=80',
                'social_links' => ['twitter' => 'https://x.com/mayadata', 'github' => 'https://github.com/mayalin'],
            ],
            [
                'name' => 'Tariq Mansoor',
                'email' => 'tariq.mansoor@bloghub.test',
                'username' => 'tariqmansoor',
                'role' => 'author',
                'specialty' => 'VP of Engineering & Tech Leadership Mentor',
                'bio' => 'Advising high-growth startups on organization design, developer velocity, and pragmatic engineering trade-offs.',
                'profile_image' => 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?auto=format&fit=crop&w=400&q=80',
                'cover_image' => 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?auto=format&fit=crop&w=1200&q=80',
                'social_links' => ['linkedin' => 'https://linkedin.com/in/tariqmansoor', 'twitter' => 'https://x.com/tariqleads'],
            ],
        ];

        $authors = [];
        foreach ($authorsData as $data) {
            $authors[] = User::firstOrCreate(
                ['email' => $data['email']],
                array_merge($data, ['password' => Hash::make('password')])
            );
        }

        // 3. READERS
        $readersData = [
            ['name' => 'Lucas Wright', 'email' => 'lucas.wright@reader.test', 'username' => 'lucasw'],
            ['name' => 'Amara Okafor', 'email' => 'amara.okafor@reader.test', 'username' => 'amarao'],
            ['name' => 'Julian Ramos', 'email' => 'julian.ramos@reader.test', 'username' => 'julianr'],
            ['name' => 'Chloe Bennett', 'email' => 'chloe.bennett@reader.test', 'username' => 'chloeb'],
        ];

        $readers = [];
        foreach ($readersData as $data) {
            $readers[] = User::firstOrCreate(
                ['email' => $data['email']],
                array_merge($data, [
                    'password' => Hash::make('password'),
                    'role' => 'reader',
                    'bio' => 'Avid tech reader, inquisitive developer, and BlogHub community member.',
                ])
            );
        }

        // 4. CATEGORIES (11 categories)
        $categoriesData = [
            [
                'name' => 'Web Development',
                'slug' => 'web-development',
                'icon' => 'bi-code-slash',
                'description' => 'Modern frontend and backend engineering, architectural patterns, and framework ecosystems.',
            ],
            [
                'name' => 'Artificial Intelligence',
                'slug' => 'artificial-intelligence',
                'icon' => 'bi-cpu',
                'description' => 'Machine learning, large language models, agentic systems, and neural networks.',
            ],
            [
                'name' => 'Cloud & DevOps',
                'slug' => 'cloud-devops',
                'icon' => 'bi-cloud-check',
                'description' => 'Infrastructure as code, Kubernetes, CI/CD automation, and multi-cloud resilience.',
            ],
            [
                'name' => 'Cyber Security',
                'slug' => 'cyber-security',
                'icon' => 'bi-shield-lock',
                'description' => 'Threat modeling, zero-trust architectures, penetration testing, and identity security.',
            ],
            [
                'name' => 'UI/UX Design',
                'slug' => 'ui-ux-design',
                'icon' => 'bi-palette',
                'description' => 'Design systems, micro-interactions, responsive typography, and user research.',
            ],
            [
                'name' => 'System Architecture',
                'slug' => 'system-architecture',
                'icon' => 'bi-diagram-3',
                'description' => 'Distributed consensus, message brokers, caching strategies, and event-driven backends.',
            ],
            [
                'name' => 'Database Engineering',
                'slug' => 'database-engineering',
                'icon' => 'bi-database',
                'description' => 'Query optimization, connection pooling, sharding, and relational data modeling.',
            ],
            [
                'name' => 'Mobile Engineering',
                'slug' => 'mobile-engineering',
                'icon' => 'bi-phone',
                'description' => 'Native Swift/Kotlin patterns, cross-platform apps, and touch interaction designs.',
            ],
            [
                'name' => 'Data Science',
                'slug' => 'data-science',
                'icon' => 'bi-graph-up-arrow',
                'description' => 'Predictive modeling, analytical pipelines, vector databases, and statistical data insights.',
            ],
            [
                'name' => 'Engineering Leadership',
                'slug' => 'engineering-leadership',
                'icon' => 'bi-briefcase',
                'description' => 'Team dynamics, engineering culture, agile refinement, and developer productivity.',
            ],
            [
                'name' => 'Emerging Tech',
                'slug' => 'emerging-tech',
                'icon' => 'bi-rocket-takeoff',
                'description' => 'Quantum compute, web assembly runtime, IoT hardware, and future innovations.',
            ],
        ];

        $categories = [];
        foreach ($categoriesData as $cData) {
            $categories[$cData['slug']] = Category::firstOrCreate(['slug' => $cData['slug']], $cData);
        }

        // 5. TAGS
        $tagsData = [
            'Laravel', 'PHP', 'JavaScript', 'Docker', 'Kubernetes', 'Security',
            'AI', 'Machine Learning', 'CSS3', 'System Design', 'PostgreSQL',
            'MySQL', 'DevOps', 'Architecture', 'UI/UX', 'Performance', 'APIs',
        ];

        $tags = [];
        foreach ($tagsData as $tName) {
            $tags[$tName] = Tag::firstOrCreate(
                ['slug' => Str::slug($tName)],
                ['name' => $tName, 'slug' => Str::slug($tName)]
            );
        }

        // 6. ARTICLES / POSTS (22 realistic, high-quality technical posts)
        $postsData = [
            [
                'author_idx' => 1, // Marcus Brooks
                'cat_slug' => 'web-development',
                'title' => 'Architecting High-Throughput Laravel Applications: From Bottlenecks to 10k RPS',
                'slug' => 'architecting-high-throughput-laravel-applications',
                'excerpt' => 'An exhaustive, real-world case study on tuning PHP-FPM workers, Octane swoole pools, query indexes, and Redis pipelines to handle immense traffic spikes gracefully.',
                'content' => 'Building enterprise web applications with Laravel is often celebrated for developer happiness and clean syntax. However, scaling an application to serve tens of thousands of requests per second demands deliberate engineering choices.

### 1. The Bottleneck Hierarchy
When examining slow Laravel applications, the culprit is almost never the PHP runtime itself. Instead, high latencies typically stem from:
- **Unoptimized Database Queries**: Missing composite indexes and unconstrained eager loading.
- **Synchronous External Calls**: Payment gateways, email dispatch, and webhook processing blocking the worker process.
- **Session and Cache Store Contention**: Default file or unpipelined database sessions choking under concurrent I/O.

### 2. Upgrading to Laravel Octane
By running Laravel with Octane powered by Swoole or RoadRunner, we eliminate the per-request bootstrapping overhead. The framework state remains booted in memory, yielding response times under 15 milliseconds for typical database reads.

```php
// config/octane.php
"max_requests" => 10000,
"garbage" => 50,
"warm" => [
    ...Octane::defaultServicesToWarm(),
],
```

### 3. Smart Query Partitioning and Redis Pipelines
Instead of issuing individual queries inside loops, leverage `chunkById()` and bulk inserts. For caching, bundle atomic reads using Redis pipelines:

```php
$stats = Cache::store("redis")->many([
    "active_subscribers",
    "today_pageviews",
    "queue_latency",
]);
```

### Conclusion
By methodically auditing query performance, deploying Laravel Octane, and delegating heavy workloads to queue workers, your Laravel application will effortlessly support massive scale with confidence.',
                'featured_image' => 'https://images.unsplash.com/photo-1555066931-4365d14bab8c?auto=format&fit=crop&w=1200&q=80',
                'status' => 'published',
                'views' => 12450,
                'reading_time' => 8,
                'published_at' => now()->subDays(2),
                'tags' => ['Laravel', 'PHP', 'Performance', 'Architecture'],
            ],
            [
                'author_idx' => 0, // Sophia Chen
                'cat_slug' => 'artificial-intelligence',
                'title' => 'Designing Autonomous AI Agents: State Machines, Memory, and Tool Calling',
                'slug' => 'designing-autonomous-ai-agents',
                'excerpt' => 'Beyond simple prompt chains: how to build resilient multi-turn agents capable of planning, error correction, and reliable tool execution.',
                'content' => 'The leap from single prompt completion to autonomous agentic systems represents the next paradigm in software engineering. When building an agent that interacts with external APIs and databases, dependability is the paramount objective.

### Core Architectural Pillars
1. **Deterministic State Graphs**: Never let an LLM run free without bounded state transitions. State machines ensure that the agent can never leap into an unauthorized state.
2. **Context Compression and Long-Term Memory**: Using vector embeddings alongside hybrid lexical search allows agents to pull historical context without overflowing context windows.
3. **Structured Tool Contracts**: Enforcing rigid JSON schemas for tools ensures that arguments can be validated by Laravel request validators before reaching your service layer.

```json
{
  "name": "lookup_customer_order",
  "description": "Retrieves the status and fulfillment timeline of a purchase order.",
  "parameters": {
    "type": "object",
    "properties": {
      "order_id": { "type": "string" },
      "verified_customer_id": { "type": "integer" }
    },
    "required": ["order_id", "verified_customer_id"]
  }
}
```

### Graceful Fallbacks and Human-in-the-Loop
When confidence intervals dip below designated thresholds, the agent must route tasks to a human supervisor rather than hallucinating answers.',
                'featured_image' => 'https://images.unsplash.com/photo-1677442136019-21780ecad995?auto=format&fit=crop&w=1200&q=80',
                'status' => 'published',
                'views' => 18920,
                'reading_time' => 10,
                'published_at' => now()->subDays(4),
                'tags' => ['AI', 'Machine Learning', 'APIs', 'Architecture'],
            ],
            [
                'author_idx' => 2, // Elena Rostova
                'cat_slug' => 'ui-ux-design',
                'title' => 'The Anatomy of a Flawless Design System: Tokens, Themes, and Visual Rhythm',
                'slug' => 'anatomy-of-flawless-design-system',
                'excerpt' => 'How to craft design tokens that translate effortlessly between Figma and production code, creating seamless dark mode transitions and cohesive typography.',
                'content' => 'A design system is far more than a shared component library—it is the unified vocabulary of your digital product.

### The Power of Design Tokens
Instead of hardcoding color hex values or pixel margins, tokens abstract your styling decisions into semantic layers:
- **Global Tokens**: Raw values (`--color-navy-900: #0f172a;`)
- **Semantic Tokens**: Contextual definitions (`--color-surface-card: var(--color-navy-900);`)
- **Component Tokens**: Granular overrides (`--btn-primary-bg: var(--color-brand-coral);`)

### Building for Both Light and Dark Themes
When building light and dark modes, simply inverting colors produces jarring contrasts. A truly polished dark mode shifts saturated colors toward warmer tones, softens pure blacks into deep charcoals, and adds subtle elevation borders.

```css
[data-bs-theme="dark"] {
  --bg-primary: #0b1120;
  --surface-card: #131d33;
  --border-subtle: rgba(255, 255, 255, 0.08);
}
```

### Micro-Interactions that Delight
Subtle button press feedback, smooth accordion unfolds, and restrained card elevation lifts breathe personality into an otherwise static interface.',
                'featured_image' => 'https://images.unsplash.com/photo-1507238691740-187a5b1d37b8?auto=format&fit=crop&w=1200&q=80',
                'status' => 'published',
                'views' => 8420,
                'reading_time' => 6,
                'published_at' => now()->subDays(6),
                'tags' => ['UI/UX', 'CSS3', 'Design Systems'],
            ],
            [
                'author_idx' => 3, // David Kim
                'cat_slug' => 'cloud-devops',
                'title' => 'Zero-Downtime Blue-Green Deployments with Kubernetes and Envoy',
                'slug' => 'zero-downtime-deployments-kubernetes-envoy',
                'excerpt' => 'A step-by-step breakdown of implementing traffic shifting, automated canary analysis, and instant rollback strategies in production clusters.',
                'content' => 'Deploying code to production should be an unremarkable, continuous event. When done correctly, end users never encounter a single 502 error or dropped websocket connection during a release.

### Canary Analysis vs. Pure Blue-Green
While blue-green deploys duplicate the entire environment, canary releases allow you to route 5% of synthetic and real user traffic to the new revision while actively monitoring error budgets and latency graphs.

```yaml
apiVersion: networking.k8s.io/v1
kind: Ingress
metadata:
  name: bloghub-canary
  annotations:
    nginx.ingress.kubernetes.io/canary: "true"
    nginx.ingress.kubernetes.io/canary-weight: "10"
```

### Database Schema Migrations Strategy
The golden rule of zero-downtime migrations: **Never make breaking schema changes in a single deploy.**
1. First deploy: Add new nullable columns or redundant tables.
2. Second deploy: Dual-write to old and new schemas.
3. Third deploy: Backfill historical rows.
4. Final deploy: Cut over reads and deprecate obsolete columns.',
                'featured_image' => 'https://images.unsplash.com/photo-1451187580459-43490279c0fa?auto=format&fit=crop&w=1200&q=80',
                'status' => 'published',
                'views' => 9780,
                'reading_time' => 9,
                'published_at' => now()->subDays(8),
                'tags' => ['DevOps', 'Kubernetes', 'Docker', 'Architecture'],
            ],
            [
                'author_idx' => 4, // Priya Patel
                'cat_slug' => 'cyber-security',
                'title' => 'Modern Web Security in 2026: Hardening Against SSRF, Token Hijacking, and API Poisoning',
                'slug' => 'modern-web-security-2026',
                'excerpt' => 'Detailed offensive and defensive techniques to safeguard your application endpoints, JWT tokens, and cloud infrastructure metadata services.',
                'content' => 'As modern application architectures disperse into microservices, third-party webhooks, and cloud functions, the attack surface has expanded significantly.

### 1. Mitigating Server-Side Request Forgery (SSRF)
When your application allows users to supply URLs for link previews or image imports, attackers will try querying internal cloud metadata IP addresses (`169.254.169.254` or internal RFC 1918 ranges). Always validate DNS resolutions and reject non-public IPs before executing requests.

### 2. Cookie Security: Why `HttpOnly` and `SameSite=Lax` Are Non-Negotiable
Storing sensitive JWT authentication tokens in browser `localStorage` leaves them open to any third-party script injection via XSS. Always store authentication tokens in `HttpOnly`, `Secure` cookies with strict path bindings.

```php
return response()->json(["status" => "authenticated"])
    ->cookie(
        "auth_token",
        $token,
        60 * 24,
        "/",
        config("session.domain"),
        true, // secure
        true, // httpOnly
        false,
        "Lax" // sameSite
    );
```

### 3. Strict Content Security Policies (CSP)
A robust Content Security Policy stops script injection in its tracks by establishing strict nonces for scripts and stylesheets.',
                'featured_image' => 'https://images.unsplash.com/photo-1563986768609-322da13575f3?auto=format&fit=crop&w=1200&q=80',
                'status' => 'published',
                'views' => 14300,
                'reading_time' => 11,
                'published_at' => now()->subDays(10),
                'tags' => ['Security', 'APIs', 'Laravel'],
            ],
            [
                'author_idx' => 5, // Liam O Connor
                'cat_slug' => 'mobile-engineering',
                'title' => 'Offline-First Mobile Architecture: Conflict-Free Replicated Data Types (CRDTs) in Practice',
                'slug' => 'offline-first-mobile-architecture-crdts',
                'excerpt' => 'How we built seamless offline synchronization into mobile apps using state-based CRDTs, SQLite local replicas, and idempotent sync endpoints.',
                'content' => 'Users expect mobile applications to function instantaneously regardless of subway tunnels or spotty cellular connectivity. Building an offline-first architecture requires a shift in how client and server reconcile state.

### The Conflict Resolution Challenge
Traditional last-write-wins (LWW) timestamping suffers from client clock drift and can lead to silent data loss. CRDTs offer mathematically provable eventual consistency across independent peers.

### Local-First Persistence with SQLite
All user interactions write immediately to a local SQLite database. A dedicated sync worker queues mutations and synchronizes with the Laravel API when connectivity resumes.',
                'featured_image' => 'https://images.unsplash.com/photo-1512941937669-90a1b58e7e9c?auto=format&fit=crop&w=1200&q=80',
                'status' => 'published',
                'views' => 6520,
                'reading_time' => 7,
                'published_at' => now()->subDays(12),
                'tags' => ['Architecture', 'Database', 'Performance'],
            ],
            [
                'author_idx' => 6, // Maya Lin
                'cat_slug' => 'database-engineering',
                'title' => 'Mastering PostgreSQL and MySQL Indexing: B-Trees, Covering Indexes, and Partial Filters',
                'slug' => 'mastering-database-indexing-techniques',
                'excerpt' => 'Dive deep into query execution plans. Learn how to transform 4-second full table scans into 1-millisecond index lookups.',
                'content' => 'Understanding how relational storage engines access disk blocks is the single most valuable skill in backend engineering.

### Understanding the B-Tree Structure
A B-Tree index maintains a sorted multi-level hierarchy of keys and row pointers. When you search for an exact match or a range query, the database traverses the tree in O(log N) operations.

### The Magic of Covering Indexes
If an index contains all the columns requested in the `SELECT` clause, the database engine avoids touching the heap table altogether:

```sql
CREATE INDEX idx_posts_category_published_covering 
ON posts (category_id, published_at, id, title, slug);
```

### Partial Indexes for Active Datasets
Why index millions of archived or soft-deleted records? A partial index only indexes rows matching your active query criteria, dramatically saving disk cache memory.',
                'featured_image' => 'https://images.unsplash.com/photo-1544383835-bda2bc66a55d?auto=format&fit=crop&w=1200&q=80',
                'status' => 'published',
                'views' => 11200,
                'reading_time' => 9,
                'published_at' => now()->subDays(14),
                'tags' => ['MySQL', 'PostgreSQL', 'Performance', 'Database'],
            ],
            [
                'author_idx' => 7, // Tariq Mansoor
                'cat_slug' => 'engineering-leadership',
                'title' => 'The Engineering Velocity Trap: Why Measuring PR Output Destroys Team Morale',
                'slug' => 'engineering-velocity-trap-pr-output',
                'excerpt' => 'DORA metrics vs. raw vanity outputs. How thoughtful tech leaders foster high psychological safety, high standards, and sustainable shipping cadence.',
                'content' => 'When engineering leaders attempt to measure developer productivity through superficial metrics—such as lines of code, commit frequency, or pull request count—they incentivize counterproductive behavior.

### Goodhart’s Law in Engineering Teams
"When a measure becomes a target, it ceases to be a good measure." Developers will split trivial changes into multiple PRs, avoid thorny refactorings, and skimp on test coverage to inflate vanity numbers.

### What Actually Matters: The Four DORA Metrics
1. **Deployment Frequency**: How often does working software reach customers?
2. **Lead Time for Changes**: How fast does a commit transition from code review to production?
3. **Change Failure Rate**: What percentage of deployments require rollbacks or hotfixes?
4. **Time to Restore Service**: When an incident occurs, how rapidly is recovery achieved?

High-performing teams achieve velocity not by working longer hours, but by eliminating CI/CD friction, reducing branch lifespans, and fostering proactive communication.',
                'featured_image' => 'https://images.unsplash.com/photo-1522071820081-009f0129c71c?auto=format&fit=crop&w=1200&q=80',
                'status' => 'published',
                'views' => 15400,
                'reading_time' => 8,
                'published_at' => now()->subDays(16),
                'tags' => ['Leadership', 'DevOps', 'Culture'],
            ],
            [
                'author_idx' => 1, // Marcus Brooks
                'cat_slug' => 'web-development',
                'title' => 'Deep Dive into Laravel 12 Service Container and Dependency Injection',
                'slug' => 'deep-dive-laravel-service-container',
                'excerpt' => 'Unlocking the elegance of contextual bindings, method injection, tag binding, and singleton lifecycles within Laravel applications.',
                'content' => 'The Laravel Service Container is the beating heart of the framework. Understanding how it automatically resolves classes via reflection enables you to write highly decoupled, testable code.

### Contextual Binding in Complex Domains
Suppose you have multiple report generators that implement the same interface, but different controllers require different implementations:

```php
$this->app->when(FinancialController::class)
    ->needs(ReportGeneratorInterface::class)
    ->give(EncryptedPdfReportGenerator::class);
```

### Tagging Services for Pipeline Processing
When building extensible plugin architectures, tagging allows you to iterate over multiple service implementations dynamically.',
                'featured_image' => 'https://images.unsplash.com/photo-1517694712202-14dd9538aa97?auto=format&fit=crop&w=1200&q=80',
                'status' => 'published',
                'views' => 7890,
                'reading_time' => 7,
                'published_at' => now()->subDays(18),
                'tags' => ['Laravel', 'PHP', 'Architecture'],
            ],
            [
                'author_idx' => 0, // Sophia Chen
                'cat_slug' => 'artificial-intelligence',
                'title' => 'Fine-Tuning vs. RAG: Choosing the Right Strategy for Domain-Specific LLMs',
                'slug' => 'fine-tuning-vs-rag-domain-llms',
                'excerpt' => 'When should you invest in LoRA fine-tuning, and when is retrieval-augmented generation with vector databases more cost-effective?',
                'content' => 'Every enterprise exploring generative AI confronts the same architectural crossroads: Do we fine-tune an open-source foundation model, or do we implement Retrieval-Augmented Generation (RAG)?

### Comparing the Two Modalities
- **RAG**: Best suited for dynamic, frequently changing knowledge bases (e.g., product catalogs, internal knowledge bases, legal policies).
- **Fine-Tuning**: Best suited for teaching the model a specific style, tone, unique dialect, or structured output syntax (e.g., custom code generation).

### Hybrid Approaches: The Gold Standard
The most robust modern architectures combine both: fine-tuning an SLM (Small Language Model) for tool execution and domain vocabulary, paired with a low-latency RAG vector retriever for up-to-the-minute facts.',
                'featured_image' => 'https://images.unsplash.com/photo-1620712943543-bcc4688e7485?auto=format&fit=crop&w=1200&q=80',
                'status' => 'published',
                'views' => 16700,
                'reading_time' => 8,
                'published_at' => now()->subDays(20),
                'tags' => ['AI', 'Machine Learning', 'Data Science'],
            ],
            [
                'author_idx' => 2, // Elena Rostova
                'cat_slug' => 'ui-ux-design',
                'title' => 'Designing for Accessibility: WCAG 2.2 Standards without Sacrificing Modern Aesthetics',
                'slug' => 'designing-for-accessibility-wcag-standards',
                'excerpt' => 'How to craft stunning, award-worthy websites that exceed contrast ratios, keyboard navigability, and screen reader standards.',
                'content' => 'Accessible design is not a constraint; it is a catalyst for cleaner, more thoughtful user experiences.

### Common Accessibility Pitfalls
1. **Low Contrast Text**: Gray text on white backgrounds may look subtle, but it renders critical copy unreadable for millions of users.
2. **Missing Focus Indicators**: Never remove `outline: none` without providing a vibrant, visible custom focus ring.
3. **Non-Descriptive Links**: Screen reader users often navigate by link lists; links labeled "click here" offer zero context.',
                'featured_image' => 'https://images.unsplash.com/photo-1581291518857-4e27b48ff24e?auto=format&fit=crop&w=1200&q=80',
                'status' => 'published',
                'views' => 6100,
                'reading_time' => 6,
                'published_at' => now()->subDays(22),
                'tags' => ['UI/UX', 'CSS3'],
            ],
            [
                'author_idx' => 3, // David Kim
                'cat_slug' => 'cloud-devops',
                'title' => 'Terraform in Production: State Locking, Terragrunt, and Multi-Account AWS Architecture',
                'slug' => 'terraform-in-production-multi-account',
                'excerpt' => 'Organizing enterprise infrastructure code with remote state locking, least-privilege IAM roles, and modular Terragrunt patterns.',
                'content' => 'Infrastructure as Code (IaC) is essential for cloud reliability. However, monolithic Terraform repositories frequently cause state lock deadlocks and disastrous blast radiuses.

### Key Rules for Safe IaC
1. **Isolate State Files**: Break your infrastructure by environment (staging, production) and component (networking, data, compute).
2. **Automated Plan Pull Requests**: Run `atlantis` or GitHub Actions to preview plan diffs before any merge to production.',
                'featured_image' => 'https://images.unsplash.com/photo-1542744094-3a31f272c490?auto=format&fit=crop&w=1200&q=80',
                'status' => 'published',
                'views' => 7450,
                'reading_time' => 8,
                'published_at' => now()->subDays(24),
                'tags' => ['DevOps', 'Architecture', 'Security'],
            ],
            [
                'author_idx' => 4, // Priya Patel
                'cat_slug' => 'cyber-security',
                'title' => 'Zero-Trust Architecture: Why Perimeter Firewalls Are No Longer Enough',
                'slug' => 'zero-trust-architecture-enterprise',
                'excerpt' => 'Never trust, always verify: moving beyond traditional VPN perimeters toward identity-aware proxies and mutual TLS microsegmentation.',
                'content' => 'The traditional castle-and-moat security model assumed that everything inside the internal corporate network was trustworthy. In modern distributed cloud topologies, this assumption is fatally flawed.

### Core Tenants of Zero-Trust
- Authenticate and authorize every request explicitly based on user identity, device posture, and geolocation.
- Enforce least privilege access principles.
- Assume breach: limit lateral movement using microsegmentation.',
                'featured_image' => 'https://images.unsplash.com/photo-1550751827-4bd374c3f58b?auto=format&fit=crop&w=1200&q=80',
                'status' => 'published',
                'views' => 9300,
                'reading_time' => 7,
                'published_at' => now()->subDays(26),
                'tags' => ['Security', 'Architecture', 'DevOps'],
            ],
            [
                'author_idx' => 6, // Maya Lin
                'cat_slug' => 'data-science',
                'title' => 'Streaming Analytics with Apache Kafka and DuckDB: Real-Time Insights Under 100ms',
                'slug' => 'streaming-analytics-kafka-duckdb',
                'excerpt' => 'How we architected a distributed event ingestion pipeline processing millions of events per second with in-process analytical query engines.',
                'content' => 'Batch processing data once a day is no longer sufficient for fast-moving businesses. Modern data architectures demand sub-second latency from event publication to visualization dashboards.',
                'featured_image' => 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?auto=format&fit=crop&w=1200&q=80',
                'status' => 'published',
                'views' => 8800,
                'reading_time' => 9,
                'published_at' => now()->subDays(28),
                'tags' => ['Data Science', 'Performance', 'Architecture'],
            ],
            [
                'author_idx' => 7, // Tariq Mansoor
                'cat_slug' => 'engineering-leadership',
                'title' => 'Building High-Trust Remote Engineering Cultures: Asynchronous Work and Documentation',
                'slug' => 'high-trust-remote-engineering-cultures',
                'excerpt' => 'Overcoming Zoom fatigue and calendar congestion by instituting written RFCs, clear PR guidelines, and asynchronous decision-making.',
                'content' => 'The most effective distributed teams do not try to replicate the office environment online. They embrace the power of the written word and intentional communication.',
                'featured_image' => 'https://images.unsplash.com/photo-1521737604893-d14cc237f11d?auto=format&fit=crop&w=1200&q=80',
                'status' => 'published',
                'views' => 12100,
                'reading_time' => 6,
                'published_at' => now()->subDays(30),
                'tags' => ['Leadership', 'Culture'],
            ],
            [
                'author_idx' => 1, // Marcus Brooks
                'cat_slug' => 'web-development',
                'title' => 'Mastering Vanilla JavaScript ES6+ in 2026: No Build Tools, Pure Performance',
                'slug' => 'mastering-vanilla-javascript-es6-performance',
                'excerpt' => 'Why modern browsers make heavy JavaScript frontend frameworks optional for 90% of content-driven websites. Native web components, IntersectionObserver, and ES modules.',
                'content' => 'Before installing dozens of npm packages, take a step back and examine the capabilities of modern browsers. Native JavaScript offers extraordinary power, blistering speed, and zero bundle compilation overhead.',
                'featured_image' => 'https://images.unsplash.com/photo-1579468118864-1b9ea3c0db4a?auto=format&fit=crop&w=1200&q=80',
                'status' => 'published',
                'views' => 17300,
                'reading_time' => 8,
                'published_at' => now()->subDays(32),
                'tags' => ['JavaScript', 'Performance', 'Web Development'],
            ],
            [
                'author_idx' => 5, // Liam O Connor
                'cat_slug' => 'mobile-engineering',
                'title' => 'Optimizing 60fps Smooth Scroll and Gesture Handlers in Modern Mobile Views',
                'slug' => 'optimizing-60fps-smooth-scroll-gestures',
                'excerpt' => 'Deep dive into hardware acceleration, layout thrashing prevention, and smooth 120Hz ProMotion display rendering.',
                'content' => 'Jank on scroll is the most noticeable visual defect on high-refresh mobile screens. Learn how to profile GPU rendering threads and avoid main thread blocking.',
                'featured_image' => 'https://images.unsplash.com/photo-1526406915894-7bcd65f60845?auto=format&fit=crop&w=1200&q=80',
                'status' => 'published',
                'views' => 5300,
                'reading_time' => 6,
                'published_at' => now()->subDays(34),
                'tags' => ['Performance', 'UI/UX'],
            ],
            [
                'author_idx' => 0, // Sophia Chen
                'cat_slug' => 'artificial-intelligence',
                'title' => 'Evaluation Frameworks for LLM Applications: Automated Benchmarking and Regression Testing',
                'slug' => 'evaluation-frameworks-llm-applications',
                'excerpt' => 'How to prevent prompt regressions, evaluate factual consistency, and run CI automated tests on generative AI pipelines.',
                'content' => 'In traditional software, unit tests provide black-and-white guarantees. In probabilistic LLM software, testing requires statistical evaluation and semantic distance scoring.',
                'featured_image' => 'https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?auto=format&fit=crop&w=1200&q=80',
                'status' => 'published',
                'views' => 9900,
                'reading_time' => 9,
                'published_at' => now()->subDays(36),
                'tags' => ['AI', 'Testing', 'Python'],
            ],
            [
                'author_idx' => 3, // David Kim
                'cat_slug' => 'cloud-devops',
                'title' => 'The Complete Guide to Multi-Region PostgreSQL Replication and Failover',
                'slug' => 'multi-region-postgresql-replication-failover',
                'excerpt' => 'Configuring physical streaming replication, Patroni quorum consensus, and cross-region connection pooling for disaster recovery.',
                'content' => 'Database outages can cripple a business in minutes. Learn how to set up resilient automated failovers that switch primary nodes in under 10 seconds.',
                'featured_image' => 'https://images.unsplash.com/photo-1544383835-bda2bc66a55d?auto=format&fit=crop&w=1200&q=80',
                'status' => 'published',
                'views' => 8400,
                'reading_time' => 10,
                'published_at' => now()->subDays(38),
                'tags' => ['PostgreSQL', 'Database', 'DevOps'],
            ],
            [
                'author_idx' => 2, // Elena Rostova
                'cat_slug' => 'ui-ux-design',
                'title' => 'Modern Editorial Typography: Grid Alignment, Kerning, and Modular Scales',
                'slug' => 'modern-editorial-typography-modular-scales',
                'excerpt' => 'The art and science of digital editorial typography. Establishing vertical rhythm, harmonious type scales, and optimal reading lengths.',
                'content' => 'Typography is the voice of your editorial content. Setting the right line-height, letter-spacing, and line-length transforms casual skimmers into engrossed readers.',
                'featured_image' => 'https://images.unsplash.com/photo-1507238691740-187a5b1d37b8?auto=format&fit=crop&w=1200&q=80',
                'status' => 'published',
                'views' => 6700,
                'reading_time' => 5,
                'published_at' => now()->subDays(40),
                'tags' => ['UI/UX', 'CSS3', 'Design Systems'],
            ],
            [
                'author_idx' => 4, // Priya Patel
                'cat_slug' => 'cyber-security',
                'title' => 'Passkeys and WebAuthn: The Complete Developer Guide to Passwordless Authentication',
                'slug' => 'passkeys-webauthn-passwordless-guide',
                'excerpt' => 'Say goodbye to compromised passwords. How to implement FIDO2 / WebAuthn biometric login in your web application seamlessly.',
                'content' => 'Passkeys leverage public-key cryptography to provide phishing-resistant, effortless biometric logins via TouchID, FaceID, or hardware security keys.',
                'featured_image' => 'https://images.unsplash.com/photo-1614064641938-3bbee52942c7?auto=format&fit=crop&w=1200&q=80',
                'status' => 'published',
                'views' => 10400,
                'reading_time' => 8,
                'published_at' => now()->subDays(42),
                'tags' => ['Security', 'JavaScript', 'APIs'],
            ],
            [
                'author_idx' => 7, // Tariq Mansoor
                'cat_slug' => 'system-architecture',
                'title' => 'Event-Driven Microservices: Dealing with Eventual Consistency and Idempotency',
                'slug' => 'event-driven-microservices-consistency-idempotency',
                'excerpt' => 'Practical solutions for the hardest problems in distributed systems: out-of-order message delivery, distributed transactions, and the Outbox Pattern.',
                'content' => 'Decoupling services with message buses offers scalability, but it introduces eventual consistency. Learn how the transactional outbox pattern guarantees reliability.',
                'featured_image' => 'https://images.unsplash.com/photo-1451187580459-43490279c0fa?auto=format&fit=crop&w=1200&q=80',
                'status' => 'published',
                'views' => 13800,
                'reading_time' => 11,
                'published_at' => now()->subDays(45),
                'tags' => ['Architecture', 'DevOps', 'Laravel'],
            ],
        ];

        $createdPosts = [];
        foreach ($postsData as $pData) {
            $author = $authors[$pData['author_idx']];
            $category = $categories[$pData['cat_slug']];

            $post = Post::firstOrCreate(
                ['slug' => $pData['slug']],
                [
                    'user_id' => $author->id,
                    'category_id' => $category->id,
                    'title' => $pData['title'],
                    'excerpt' => $pData['excerpt'],
                    'content' => $pData['content'],
                    'featured_image' => $pData['featured_image'],
                    'status' => $pData['status'],
                    'views' => $pData['views'],
                    'reading_time' => $pData['reading_time'],
                    'published_at' => $pData['published_at'],
                ]
            );

            // Attach tags
            $tagIds = [];
            foreach ($pData['tags'] as $tagName) {
                if (isset($tags[$tagName])) {
                    $tagIds[] = $tags[$tagName]->id;
                }
            }
            $post->tags()->syncWithoutDetaching($tagIds);

            $createdPosts[] = $post;
        }

        // 7. COMMENTS (50+ realistic technical comments)
        $commentTemplates = [
            "Tremendous write-up! We recently migrated our queue workers to this exact architecture and witnessed a 4x reduction in CPU usage during peak morning hours.",
            "Could you elaborate more on how this handles connection drops during failover? Do you recommend exponential backoff on the client layer?",
            "This is by far the most lucid explanation of this topic I've read in years. The code snippet on line 14 answered a question I've been stuck on all week.",
            "Really appreciate the realistic benchmarks instead of the usual synthetic numbers. Bookmarked for our next engineering architecture review.",
            "A quick note on edge cases: if memory limits are reached under Swoole, ensure you configure max_requests properly to prevent unbounded leaks.",
            "Excellent breakdown of design tokens. We adopted this exact hierarchy in our Figma-to-code workflow and our designers and engineers finally speak the same language.",
            "How does this impact initial Time to First Byte (TTFB) compared to traditional SSR? Did you measure client-side hydration overhead?",
            "Security by design is often neglected until it's too late. The point on strict CSP nonces should be mandatory reading for all full-stack engineers.",
            "Fascinating perspective on DORA metrics. In my experience as an engineering manager, focus on lead time for changes naturally pulls deployment frequency upward.",
            "Thanks for sharing this! Would love to see a follow-up article detailing how to write automated integration tests for these scenarios.",
            "We had a major incident last month caused by a missing composite index just like the one highlighted here. Super valuable practical advice.",
            "Passkeys have transformed our login conversion rates. Users genuinely appreciate not having to remember 16-character passwords with special symbols."
        ];

        $commenters = array_merge($readers, $authors);
        $totalComments = 0;

        foreach ($createdPosts as $post) {
            // Add 2 to 4 comments per post to achieve 50+ total comments
            $numComments = rand(2, 4);
            for ($i = 0; $i < $numComments; $i++) {
                $commenter = $commenters[array_rand($commenters)];
                $content = $commentTemplates[array_rand($commentTemplates)];

                Comment::create([
                    'post_id' => $post->id,
                    'user_id' => $commenter->id,
                    'name' => $commenter->name,
                    'email' => $commenter->email,
                    'content' => $content,
                    'status' => 'approved',
                    'created_at' => $post->published_at->addHours(rand(1, 48)),
                ]);
                $totalComments++;
            }
        }

        // 8. TESTIMONIALS (10 realistic testimonials)
        $testimonialsData = [
            [
                'name' => 'Sarah Jenkins',
                'role_or_title' => 'Chief Technology Officer',
                'company' => 'Starlight Financial Systems',
                'avatar' => 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&w=200&q=80',
                'quote' => 'BlogHub has become mandatory reading across our 200+ engineering organization. The depth of architectural insight and code quality is unmatched.',
                'rating' => 5,
                'is_featured' => true,
            ],
            [
                'name' => 'Jonathan Reyes',
                'role_or_title' => 'VP of Infrastructure',
                'company' => 'CloudScale Networks',
                'avatar' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=200&q=80',
                'quote' => 'No clickbait, no superficial summaries—just rigorous, production-grade engineering articles written by veterans who have been in the trenches.',
                'rating' => 5,
                'is_featured' => true,
            ],
            [
                'name' => 'Claire Dupont',
                'role_or_title' => 'Head of Design',
                'company' => 'Atelier Interactive',
                'avatar' => 'https://images.unsplash.com/photo-1580489944761-15a19d654956?auto=format&fit=crop&w=200&q=80',
                'quote' => 'The editorial aesthetic of BlogHub proves that deep technical content and gorgeous, modern typography can co-exist harmoniously.',
                'rating' => 5,
                'is_featured' => true,
            ],
            [
                'name' => 'Arun Krishnamurthy',
                'role_or_title' => 'Principal Staff Architect',
                'company' => 'HyperScale Labs',
                'avatar' => 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&w=200&q=80',
                'quote' => 'Articles on database indexing and zero-downtime migrations saved us weeks of trial and error before our Black Friday deployment.',
                'rating' => 5,
                'is_featured' => true,
            ],
            [
                'name' => 'Dr. Miriam Al-Hassan',
                'role_or_title' => 'AI Research Director',
                'company' => 'Cognitive Frontier AI',
                'avatar' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=200&q=80',
                'quote' => 'A refreshing haven of high-caliber discourse on machine learning systems and responsible AI evaluation frameworks.',
                'rating' => 5,
                'is_featured' => true,
            ],
            [
                'name' => 'Henrik Lindqvist',
                'role_or_title' => 'Senior Site Reliability Engineer',
                'company' => 'Nordic Stream Media',
                'avatar' => 'https://images.unsplash.com/photo-1519085360753-af0119f7cbe7?auto=format&fit=crop&w=200&q=80',
                'quote' => 'The DevOps teardowns provide immediate, actionable blueprints that we implemented in our Kubernetes clusters the same afternoon.',
                'rating' => 5,
                'is_featured' => true,
            ],
            [
                'name' => 'Valerie Zhao',
                'role_or_title' => 'Lead Frontend Engineer',
                'company' => 'Pulse Commerce',
                'avatar' => 'https://images.unsplash.com/photo-1544005313-94ddf0286df2?auto=format&fit=crop&w=200&q=80',
                'quote' => 'BlogHub champions clean, performant vanilla JavaScript and standards-compliant CSS. It is a breath of fresh air in today’s bloated web landscape.',
                'rating' => 5,
                'is_featured' => true,
            ],
            [
                'name' => 'Mateo Silva',
                'role_or_title' => 'Engineering Manager',
                'company' => 'DevVelocity Labs',
                'avatar' => 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?auto=format&fit=crop&w=200&q=80',
                'quote' => 'The articles on psychological safety and team metrics helped re-align our sprint retrospectives and boosted developer retention.',
                'rating' => 5,
                'is_featured' => true,
            ],
            [
                'name' => 'Rachel Stern',
                'role_or_title' => 'Founder & CEO',
                'company' => 'NextGen SaaS Hub',
                'avatar' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=200&q=80',
                'quote' => 'BlogHub is our go-to resource when vetting technological stacks and architectural decisions for seed-stage portfolio companies.',
                'rating' => 5,
                'is_featured' => true,
            ],
            [
                'name' => 'Devon Washington',
                'role_or_title' => 'Security Operations Lead',
                'company' => 'Aegis Cyber Defense',
                'avatar' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=200&q=80',
                'quote' => 'Realistic vulnerability breakdowns with practical remediation steps. BlogHub sets the benchmark for modern cybersecurity publishing.',
                'rating' => 5,
                'is_featured' => true,
            ],
        ];

        foreach ($testimonialsData as $tData) {
            Testimonial::firstOrCreate(['name' => $tData['name']], $tData);
        }

        // 9. LIKES & BOOKMARKS
        foreach ($createdPosts as $post) {
            // Randomly like posts
            $likerCount = rand(2, 5);
            $randomUsers = array_slice($commenters, 0, $likerCount);
            foreach ($randomUsers as $u) {
                Like::firstOrCreate(['post_id' => $post->id, 'user_id' => $u->id]);
            }

            // Randomly bookmark posts
            if (rand(0, 1) === 1) {
                $bookmarker = $readers[array_rand($readers)];
                Bookmark::firstOrCreate(['post_id' => $post->id, 'user_id' => $bookmarker->id]);
            }
        }

        // 10. FOLLOWERS (readers follow authors, authors follow authors)
        foreach ($readers as $reader) {
            $followedAuthors = array_slice($authors, 0, rand(2, 5));
            foreach ($followedAuthors as $fAuthor) {
                $reader->following()->syncWithoutDetaching([$fAuthor->id]);
            }
        }

        // 11. NEWSLETTER SUBSCRIBERS
        $subscribers = [
            'alex@techleaders.io',
            'jordan.m@fastscale.dev',
            'dev-weekly@frontendnews.com',
            'sarah.codes@gmail.com',
            'kevin.zhang@enterprise.cloud',
            'maria.g@cyberpulse.net',
            'info@modernphp.org',
            'subscribers@bloghub.test',
        ];
        foreach ($subscribers as $email) {
            NewsletterSubscriber::firstOrCreate(['email' => $email], ['status' => 'active']);
        }

        // 12. CONTACT MESSAGES
        $messages = [
            [
                'name' => 'Evelyn Carter',
                'email' => 'evelyn.c@techventure.com',
                'subject' => 'Guest Author Proposal: Quantum Cryptography in Fintech',
                'message' => 'Hello BlogHub editorial team, I am a cryptography researcher at TechVenture and would love to submit a deep-dive piece on post-quantum lattice algorithms.',
                'is_read' => true,
            ],
            [
                'name' => 'Nathaniel Drake',
                'email' => 'nathaniel@archconsult.co',
                'subject' => 'Corporate Subscription & Team Licensing',
                'message' => 'We are looking to sponsor 50 premium accounts for our engineering team. Please let us know if enterprise invoicing is supported.',
                'is_read' => false,
            ],
        ];
        foreach ($messages as $msg) {
            ContactMessage::firstOrCreate(['email' => $msg['email'], 'subject' => $msg['subject']], $msg);
        }
    }
}
