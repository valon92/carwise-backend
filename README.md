# 🚗 CarWise AI - Modern Vehicle Report Management System

**Sistem modern për menaxhimin e raporteve të automjeteve me integrim AI**

Një aplikacion i avancuar i ndërtuar me Laravel 12, Vue.js 3 dhe Inertia.js për menaxhimin inteligjent të raporteve të problemeve të automjeteve, i pajisur me AI assistant dhe analitika të avancuara.

## 🌟 Veçoritë Kryesore

### 🤖 AI Integration
- **AI Assistant** - Chatbot inteligjent për ndihmë në kohë reale
- **Analizë e Automatizuar** - AI analizon raportet dhe jep rekomandime
- **Vlerësim i Kostos** - Parashikim i saktë të kostos së riparimit
- **Rekomandime të Personalizuara** - Sugjerime bazuar në historikun e përdoruesit

### 📊 Analytics & Insights
- **Dashboard i Avancuar** - Statistikat në kohë reale
- **AI Performance Metrics** - Analitika e performancës së AI
- **Trend Analysis** - Analizë e tendencave dhe parashikime
- **User Insights** - Statistikat personale të përdoruesit

### 🔧 Vehicle Management
- **Profili i Plotë i Automjetit** - Informacion i detajuar për çdo automjet
- **Service Tracking** - Gjurmo servisin dhe mirëmbajtjen
- **Warranty Management** - Menaxhim i garancisë dhe sigurimit
- **Maintenance History** - Historiku i plotë i mirëmbajtjes

### 📱 Modern UI/UX
- **Responsive Design** - Përshtatet me të gjitha pajisjet
- **Real-time Updates** - Përditësime në kohë reale
- **Interactive Charts** - Grafikët ndërveprues
- **Dark/Light Mode** - Tema e personalizueshme

## 🛠️ Teknologjitë

### Backend
- **Laravel 12** - Framework PHP modern
- **Laravel Sanctum** - Autentikim API
- **SQLite/MySQL** - Bazë të dhënash
- **Inertia.js** - SPA experience
- **Spatie Packages** - Media, Permissions, Activity Log
- **Pusher** - Real-time notifications

### Frontend
- **Vue.js 3** - Framework JavaScript me Composition API
- **Tailwind CSS** - Utility-first CSS framework
- **Vite** - Build tool modern
- **Chart.js** - Interactive charts
- **Headless UI** - Accessible UI components

### AI & Analytics
- **Custom AI Service** - AI logic për analizë dhe rekomandime
- **Intent Recognition** - Njohja e qëllimit të përdoruesit
- **Confidence Scoring** - Vlerësimi i besueshmërisë
- **Predictive Analytics** - Analitika parashikuese

## 🚀 Instalimi

### Kërkesat
- PHP 8.2+
- Composer
- Node.js 18+
- npm

### Hapat e Instalimit

1. **Klononi repository-n:**
```bash
git clone https://github.com/yourusername/carwise-ai.git
cd carwise-ai
```

2. **Instaloni varësitë PHP:**
```bash
composer install
```

3. **Kopjoni file-in e konfigurimit:**
```bash
cp .env.example .env
```

4. **Gjeneroni çelësin e aplikacionit:**
```bash
php artisan key:generate
```

5. **Krijoni bazën e të dhënave:**
```bash
# Për SQLite
touch database/database.sqlite

# OSE për MySQL/PostgreSQL, konfiguroni .env
```

6. **Ekzekutoni migracionet:**
```bash
php artisan migrate
```

7. **Instaloni varësitë JavaScript:**
```bash
npm install
```

8. **Ndërtoni assets:**
```bash
npm run build
```

9. **Publikoni konfigurimet e paketave:**
```bash
php artisan vendor:publish --provider="Spatie\MediaLibrary\MediaLibraryServiceProvider" --tag="migrations"
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider" --tag="migrations"
php artisan vendor:publish --provider="Spatie\Activitylog\ActivitylogServiceProvider" --tag="migrations"
php artisan migrate
```

## 🏃‍♂️ Ekzekutimi

### Development
```bash
# Terminal 1: Laravel server
php artisan serve

# Terminal 2: Vite dev server
npm run dev
```

### Production
```bash
npm run build
php artisan serve
```

## 📊 Struktura e Projektit

### Modelet
- **User** - Përdoruesit me role dhe permissions
- **Report** - Raportet e automjeteve me AI analysis
- **Vehicle** - Automjetet me service history
- **AiChat** - Historiku i bisedave me AI
- **Notification** - Njoftimet në kohë reale

### API Endpoints
- `POST /api/register` - Regjistrim përdoruesish
- `POST /api/login` - Kyçje
- `GET /api/reports` - Lista e raporteve
- `POST /api/reports` - Krijim raporti të ri
- `GET /api/reports/{id}` - Shfaqje raporti specifik
- `POST /ai/chat/message` - Mesazh në AI
- `GET /ai/analytics` - Analitikat e AI

### Funksionalitetet AI
- ✅ Intent Recognition - Njohja e qëllimit
- ✅ Confidence Scoring - Vlerësimi i besueshmërisë
- ✅ Cost Estimation - Vlerësimi i kostos
- ✅ Severity Assessment - Vlerësimi i rëndësisë
- ✅ Parts Recommendation - Rekomandimi i pjesëve
- ✅ Predictive Analytics - Analitika parashikuese

## 🔧 Konfigurimi

### AI Configuration
```php
// config/ai.php
return [
    'model' => 'carwise-ai-v1',
    'confidence_threshold' => 0.7,
    'max_tokens' => 1000,
    'response_timeout' => 30,
];
```

### CORS
CORS është konfiguruar për `http://localhost:5173` në `config/cors.php`

### Autentikimi
Përdor Laravel Sanctum për API authentication

### Media Library
Konfiguruar për ruajtjen e imazheve dhe dokumenteve

## 🧪 Testimi

```bash
# Run all tests
php artisan test

# Run specific test suite
php artisan test --filter=AiServiceTest

# Run with coverage
php artisan test --coverage
```

## 📝 Migracionet

```bash
# Krijimi i migracionit të ri
php artisan make:migration create_table_name

# Ekzekutimi i migracionit
php artisan migrate

# Rollback
php artisan migrate:rollback

# Refresh me seeders
php artisan migrate:fresh --seed
```

## 🎯 Funksionalitetet AI

### Chat Assistant
- **Natural Language Processing** - Përpunimi i gjuhës natyrore
- **Context Awareness** - Ndërgjegjësia e kontekstit
- **Multi-language Support** - Mbështetje për shumë gjuhë
- **Session Management** - Menaxhim i sesioneve

### Analytics Engine
- **Performance Metrics** - Metrikat e performancës
- **Trend Analysis** - Analizë e tendencave
- **Predictive Modeling** - Modelimi parashikues
- **User Behavior Analysis** - Analizë e sjelljes së përdoruesit

### Recommendation System
- **Parts Recommendation** - Rekomandimi i pjesëve
- **Service Scheduling** - Programimi i servisit
- **Cost Optimization** - Optimizimi i kostos
- **Preventive Maintenance** - Mirëmbajtja parandaluese

## 🤝 Kontributimi

1. Fork repository-n
2. Krijoni një branch të ri (`git checkout -b feature/amazing-feature`)
3. Commit ndryshimet (`git commit -m 'Add amazing feature'`)
4. Push në branch (`git push origin feature/amazing-feature`)
5. Hapni një Pull Request

## 📄 Licenca

Ky projekt është i licencuar nën MIT License.

## 👨‍💻 Autor

**Valon Sylejmani**

## 🚀 Roadmap

### Version 2.0 (Coming Soon)
- [ ] Mobile App (React Native)
- [ ] Advanced AI Models (GPT-4 Integration)
- [ ] Real-time Video Analysis
- [ ] IoT Integration
- [ ] Blockchain for Vehicle History
- [ ] Multi-tenant Architecture

### Version 3.0 (Future)
- [ ] AR/VR Support
- [ ] Voice Assistant
- [ ] Autonomous Vehicle Integration
- [ ] Advanced Predictive Analytics
- [ ] Global Marketplace Integration

---

⭐ Nëse ju pëlqen ky projekt, jepni një star në GitHub!

## 📞 Kontakti

- **Email:** valon@carwise.ai
- **Website:** https://carwise.ai
- **LinkedIn:** https://linkedin.com/in/valonsylejmani
- **Twitter:** https://twitter.com/valonsylejmani

## 🙏 Falënderimet

Faleminderit për përdorimin e CarWise AI! Ky projekt është zhvilluar me pasion për të përmirësuar eksperiencën e menaxhimit të automjeteve përmes teknologjisë moderne dhe AI.
