import React, { useState } from 'react';
import Header from './components/Header';
import Hero from './components/Hero';
import Features from './components/Features';
import Dashboard from './components/Dashboard';
import Footer from './components/Footer';
import LoginModal from './components/LoginModal';

function App() {
  const [token, setToken] = useState(() => localStorage.getItem('fossestate_token'));
  const [showLogin, setShowLogin] = useState(false);

  const handleLogin = (newToken) => {
    localStorage.setItem('fossestate_token', newToken);
    setToken(newToken);
    setShowLogin(false);
  };

  const handleLogout = () => {
    localStorage.removeItem('fossestate_token');
    setToken(null);
  };

  return (
    <div className="min-h-screen flex flex-col bg-gradient-to-br from-primary/10 to-secondary/10">
      <Header
        token={token}
        onLoginClick={() => setShowLogin(true)}
        onLogout={handleLogout}
      />
      <main className="flex-grow max-w-6xl mx-auto px-8 py-12 w-full">
        <Hero />
        <Features />
        <Dashboard token={token} />
      </main>
      <Footer />
      {showLogin && (
        <LoginModal onClose={() => setShowLogin(false)} onLogin={handleLogin} />
      )}
    </div>
  );
}

export default App;
