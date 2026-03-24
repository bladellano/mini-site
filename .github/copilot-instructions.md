# Mini Site - AI Coding Agent Instructions

## Arquitetura do Projeto

Este é um **micro-framework PHP personalizado** construído do zero, sem frameworks como Laravel ou Symfony. A arquitetura segue o padrão MVC simplificado:

- **Roteamento manual** via array em `routes/web.php` mapeando URLs para [Controller::class, 'método']
- **Front controller** em `public/index.php` processa todas as requisições via Apache mod_rewrite
- **Controllers** em `app/controllers/` com namespace `App\Controllers`
- **Views** são arquivos PHP puros em `app/views/` incluídos via `require_once`
- **PSR-4 autoloading** com namespace `App\` mapeado para `app/`

### Fluxo de Requisição
1. **`.htaccess` (raiz)** redireciona tudo para `public/` (se não estiver em ambiente Docker)
2. **`public/.htaccess`** redireciona requisições não-arquivo/diretório para `index.php` (`[QSA,L]`)
3. `index.php` carrega rotas de `web.php` e faz o dispatch baseado no URI
4. Controller instanciado dinamicamente executa o método especificado
5. Método do controller carrega a view correspondente

### Arquivos `.htaccess`

**`.htaccess` na raiz** (para servidores sem document root em `public/`):
```apache
<IfModule mod_rewrite.c>
 RewriteEngine On
 RewriteRule ^(.*)$ public/$1 [L]
</IfModule>
```

**`public/.htaccess`** (regras principais):
```apache
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f   # Ignora arquivos físicos
RewriteCond %{REQUEST_FILENAME} !-d   # Ignora diretórios físicos
RewriteRule ^ index.php [QSA,L]       # QSA=Query String Append, L=Last rule
```

## Ambiente de Desenvolvimento

### Docker Setup (Preferencial)
```bash
docker-compose up -d        # Inicia o servidor PHP 8.2 + Apache
```
- Acesso: `http://localhost:8080`
- Volume montado: código sincronizado automaticamente
- Apache configurado para servir de `public/` com mod_rewrite habilitado

### Estrutura Docker
- **PHP 8.2-apache** base image
- Document root: `/var/www/html/public`
- `AllowOverride All` configurado no Dockerfile via sed em `000-default.conf`
- Porta 8080 externa mapeada para porta 80 interna
- Volume montado: `./:/var/www/html` (sincronização automática)

### Comandos Úteis
```bash
docker-compose up -d              # Inicia em background
docker-compose logs web           # Ver logs do Apache
docker-compose down               # Para e remove containers
docker exec -it <container-id> bash  # Entrar no container
```

## Convenções do Código

### Adicionando Novas Rotas
1. Criar controller em `app/controllers/` com namespace `App\Controllers`
2. Criar view em `app/views/`
3. Registrar em `routes/web.php`:
   ```php
   '/nova-rota' => [NomeController::class, 'metodo'],
   ```

### Padrão de Controllers
```php
namespace App\Controllers;

class ExemploController
{
    public function index()
    {
        require_once '../app/views/exemplo.php';
    }
}
```
- Namespace obrigatório: `App\Controllers`
- Views carregadas com caminho relativo de `public/`
- Sem injeção de dependências ou traits de framework

### Views
- HTML puro com PHP embutido (sem template engine)
- Caminhos internos usam URLs relativas sem `.php`
- Exemplo: `<a href="/about">` (não `/about.php`)

## Pontos de Atenção

### Limitações Atuais
- **Sem ORM**: Nenhum sistema de banco de dados configurado ainda
- **Sem validação/middleware**: Implementação manual necessária
- **Sem tratamento de verbos HTTP**: Apenas GET funciona nativamente
- **Sem sistema de templates**: Views são PHP puro
- **Sem gerenciamento de assets**: CSS/JS servidos diretamente sem build
- **Sem testes automatizados**: PHPUnit ou similar não configurado
- **404 manual**: Tratamento básico inline em `index.php`
- **Sem autoloading de rotas**: Todas as rotas devem ser explicitamente declaradas

### Considerações Importantes
- **Caminhos relativos**: Views assumem execução a partir de `public/`
- **Namespace obrigatório**: Controllers DEVEM usar `App\Controllers`
- **PSR-4**: Composer autoload gerencia o carregamento de classes

## Deploy

### Heroku
O projeto está configurado para deploy no Heroku via `heroku/heroku-buildpack-php`:
```bash
# Comando para iniciar (configurado via Procfile ou buildpack)
heroku-php-apache2 public/
```
- Buildpack detecta automaticamente PHP
- Apache servindo da pasta `public/`
- Composer instala dependências no build

## Testes

### Teste Manual Local
```bash
# Via navegador
http://localhost:8080/        # Página home
http://localhost:8080/about   # Página about
http://localhost:8080/invalid # Deve retornar 404

# Via curl
curl http://localhost:8080/
curl http://localhost:8080/about
curl -I http://localhost:8080/invalid  # Verificar status 404
```

## Debugging

- Logs do Apache dentro do container: `docker-compose logs web`
- Para entrar no container: `docker exec -it [container-id] bash`
- Erros PHP aparecem no navegador (sem error handler customizado)
