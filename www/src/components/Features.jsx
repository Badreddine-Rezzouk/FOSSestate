import React from 'react';

const FeatureCard = ({ icon, title, description }) => (
  <div className="bg-white rounded-lg p-8 shadow-md hover:shadow-lg hover:scale-105 transition text-center">
    <div className="text-5xl mb-4">{icon}</div>
    <h3 className="text-xl font-bold text-gray-800 mb-3">{title}</h3>
    <p className="text-gray-600 leading-relaxed">{description}</p>
  </div>
);

const Features = () => {
  const features = [
    {
      icon: '🏢',
      title: 'Property Management',
      description: 'Easily manage multiple properties with detailed information, documents, and maintenance schedules.'
    },
    {
      icon: '👥',
      title: 'Tenant Tracking',
      description: 'Keep track of tenants, lease agreements, payment history, and communication records.'
    },
    {
      icon: '💰',
      title: 'Financial Reports',
      description: 'Generate comprehensive financial reports, track expenses, and manage rent collections.'
    },
    {
      icon: '📅',
      title: 'Maintenance Scheduling',
      description: 'Schedule and track maintenance tasks, repairs, and inspections for all properties.'
    },
    {
      icon: '📱',
      title: 'Mobile Access',
      description: 'Access your property information on the go with our mobile-friendly application.'
    },
    {
      icon: '🔒',
      title: 'Secure & Open Source',
      description: 'Built with security in mind and completely open source for transparency and customization.'
    }
  ];

  return (
    <section id="features" className="mb-12">
      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        {features.map((feature, index) => (
          <FeatureCard key={index} {...feature} />
        ))}
      </div>
    </section>
  );
};

export default Features;
