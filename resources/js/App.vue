<template>
  <div id="app">
    <Navbar />
    <main class="content">
      <router-view />
    </main>

    <footer class="site-footer">
      <div class="container">
        <div class="footer-grid">
          <div>
            <h3 class="footer-title">EduAcademy</h3>
            <p class="footer-text">Quality courses. Smarter learning.</p>
          </div>

         

          <div>
            <h4 class="footer-subtitle">Contact</h4>
            <p class="footer-text">support@eduacademy.com</p>
            <p class="footer-text">+91 9876543210</p>
          </div>
        </div>

        <p class="copyright">
          &copy; 2025 EduAcademy. All rights reserved.
        </p>
      </div>
    </footer>
  </div>
</template>

<style>
html, body {
  height: 100%;
  margin: 0;
}
#app {
  min-height: 100vh;
  display: flex;
  flex-direction: column;
}
.content {
  flex: 1;
}

/* footer */
.site-footer {
  background: white;
  border-top: 1px solid #e5e7eb;
  margin-top: 3rem;
  padding: 2rem 0;
}

.footer-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 2rem;
}

.footer-title {
  font-size: 1.25rem;
  font-weight: 700;
  margin-bottom: .5rem;
}

.footer-subtitle {
  font-size: 1rem;
  font-weight: 600;
  margin-bottom: .5rem;
}

.footer-text {
  margin: .25rem 0;
  color: #6b7280;
}

.footer-links {
  list-style: none;
  padding: 0;
}

.footer-links li {
  margin: .25rem 0;
}

.footer-links a {
  color: #3b82f6;
  text-decoration: none;
}

.footer-links a:hover {
  text-decoration: underline;
}

.container {
  max-width: 1120px;
  margin: 0 auto;
  padding: 3rem 1rem;
  text-align: left;
}

copyright {
  text-align: center;
  margin-top: 2rem;
  color: #6b7280;
  font-size: .875rem;
}
</style>

<script>

import Navbar from './components/Navbar.vue';

export default {
  name: 'App',
  components: { Navbar },
};
</script>
