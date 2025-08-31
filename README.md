# 🚗 CarWise Backend

**Sistem për Menaxhimin e Raporteve të Automjeteve**

Një aplikacion modern i ndërtuar me Laravel 12, Vue.js 3 dhe Inertia.js për menaxhimin e raporteve të problemeve të automjeteve.

## 🛠️ Teknologjitë

### Backend
- **Laravel 12** - Framework PHP modern
- **Laravel Sanctum** - Autentikim API
- **SQLite** - Bazë të dhënash
- **Inertia.js** - SPA experience

### Frontend
- **Vue.js 3** - Framework JavaScript me Composition API
- **Tailwind CSS** - Utility-first CSS framework
- **Vite** - Build tool modern

## 🚀 Instalimi

### Kërkesat
- PHP 8.2+
- Composer
- Node.js 18+
- npm

### Hapat e Instalimit

1. **Klononi repository-n:**
```bash
git clone https://github.com/yourusername/carwise-backend.git
cd carwise-backend
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

5. **Krijoni bazën e të dhënave SQLite:**
```bash
touch database/database.sqlite
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
- **User** - Përdoruesit e sistemit
- **Report** - Raportet e automjeteve

### API Endpoints
- `POST /api/register` - Regjistrim përdoruesish
- `POST /api/login` - Kyçje
- `GET /api/reports` - Lista e raporteve
- `POST /api/reports` - Krijim raporti të ri
- `GET /api/reports/{id}` - Shfaqje raporti specifik

### Funksionalitetet
- ✅ Autentikim dhe autorizim
- ✅ CRUD operacione për raportet
- ✅ API RESTful
- ✅ Frontend modern me Vue.js
- ✅ Responsive design me Tailwind CSS

## 🔧 Konfigurimi

### CORS
CORS është konfiguruar për `http://localhost:5173` në `config/cors.php`

### Autentikimi
Përdor Laravel Sanctum për API authentication

## 🧪 Testimi

```bash
php artisan test
```

## 📝 Migracionet

```bash
# Krijimi i migracionit të ri
php artisan make:migration create_table_name

# Ekzekutimi i migracionit
php artisan migrate

# Rollback
php artisan migrate:rollback
```

## 🎯 Qëllimi

Ky aplikacion lejon përdoruesit të:
- Regjistrohen dhe kyçen në sistem
- Krijojnë raporte për automjetet e tyre
- Shohin raportet e tyre personale
- Menaxhojnë informacionet e automjeteve (marka, model, vit, VIN)

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

---

⭐ Nëse ju pëlqen ky projekt, jepni një star në GitHub!
