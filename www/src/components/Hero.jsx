import React from 'react';

const Hero = () => {
  const getStarted = () => {
    alert('Getting started! Redirecting to registration...');
    // window.location.href = '/api/register';
  };

  const learnMore = () => {
    const element = document.getElementById('features');
    element?.scrollIntoView({ behavior: 'smooth' });
  };

  return (
    <section 
      id="home"
      className="bg-white rounded-lg p-16 text-center shadow-lg mb-12"
    >
      <h1 className="text-5xl font-bold text-gray-800 mb-4">
        Welcome to FOSSestate
      </h1>
      <p className="text-xl text-gray-600 mb-8 leading-relaxed">
        Your modern, open-source property management solution. Manage properties, tenants, and finances all in one place.
      </p>
      <div className="flex gap-4 justify-center flex-wrap">
        <button 
          onClick={getStarted}
          className="px-10 py-3 bg-primary text-white font-semibold rounded hover:bg-secondary transition transform hover:scale-105"
        >
          Get Started
        </button>
        <button 
          onClick={learnMore}
          className="px-10 py-3 bg-white text-primary font-semibold border-2 border-primary rounded hover:bg-primary hover:text-white transition"
        >
          Learn More
        </button>
      </div>
    </section>
  );
};

export default Hero;
