import React from 'react';
import Header from './components/Header';
import Hero from './components/Hero';
import Features from './components/Features';
import Dashboard from './components/Dashboard';
import Footer from './components/Footer';

function App() {
  return (
    <div className="min-h-screen flex flex-col bg-gradient-to-br from-primary/10 to-secondary/10">
      <Header />
      <main className="flex-grow max-w-6xl mx-auto px-8 py-12 w-full">
        <Hero />
        <Features />
        <Dashboard />
      </main>
      <Footer />
    </div>
  );
}

export default App;
