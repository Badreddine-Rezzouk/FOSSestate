import React, { useState } from 'react';

const StatBox = ({ number, label }) => (
  <div className="bg-gradient-to-br from-primary to-secondary text-white p-6 rounded-lg text-center">
    <div className="text-4xl font-bold mb-2">{number}</div>
    <div className="text-lg opacity-90">{label}</div>
  </div>
);

const PropertyRow = ({ name, address, tenants, status, onView, onEdit, onDelete }) => (
  <tr className="border-b hover:bg-gray-50">
    <td className="px-4 py-3">{name}</td>
    <td className="px-4 py-3">{address}</td>
    <td className="px-4 py-3">{tenants}</td>
    <td className="px-4 py-3">
      <span className={`font-semibold ${status === 'Active' ? 'text-green-600' : 'text-yellow-600'}`}>
        {status}
      </span>
    </td>
    <td className="px-4 py-3 flex gap-2">
      <button onClick={onView} className="px-3 py-1 bg-primary text-white rounded text-sm hover:opacity-80 transition">View</button>
      <button onClick={onEdit} className="px-3 py-1 bg-green-500 text-white rounded text-sm hover:opacity-80 transition">Edit</button>
      <button onClick={onDelete} className="px-3 py-1 bg-red-500 text-white rounded text-sm hover:opacity-80 transition">Delete</button>
    </td>
  </tr>
);

const Dashboard = () => {
  const [properties] = useState([
    { id: 1, name: 'Sunset Apartments', address: '123 Main St, Downtown', tenants: 8, status: 'Active' },
    { id: 2, name: 'North Ridge Residences', address: '456 Oak Ave, North Side', tenants: 12, status: 'Active' },
    { id: 3, name: 'Central Plaza', address: '789 Plaza Blvd, Center', tenants: 15, status: 'Maintenance' }
  ]);

  const handleView = (id) => alert(`Viewing property ${id}`);
  const handleEdit = (id) => alert(`Editing property ${id}`);
  const handleDelete = (id) => alert(`Deleting property ${id}`);

  return (
    <section id="properties" className="bg-white rounded-lg p-8 shadow-lg">
      <h2 className="text-4xl font-bold text-gray-800 mb-8">Dashboard Overview</h2>
      
      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <StatBox number="24" label="Total Properties" />
        <StatBox number="156" label="Active Tenants" />
        <StatBox number="$45.2K" label="Monthly Revenue" />
        <StatBox number="8" label="Maintenance Tasks" />
      </div>

      <h3 className="text-2xl font-bold text-gray-800 mb-4">Recent Properties</h3>
      <div className="overflow-x-auto">
        <table className="w-full border-collapse">
          <thead className="bg-gray-100">
            <tr>
              <th className="px-4 py-3 text-left font-semibold text-gray-800">Property Name</th>
              <th className="px-4 py-3 text-left font-semibold text-gray-800">Address</th>
              <th className="px-4 py-3 text-left font-semibold text-gray-800">Tenants</th>
              <th className="px-4 py-3 text-left font-semibold text-gray-800">Status</th>
              <th className="px-4 py-3 text-left font-semibold text-gray-800">Actions</th>
            </tr>
          </thead>
          <tbody>
            {properties.map(property => (
              <PropertyRow
                key={property.id}
                name={property.name}
                address={property.address}
                tenants={property.tenants}
                status={property.status}
                onView={() => handleView(property.id)}
                onEdit={() => handleEdit(property.id)}
                onDelete={() => handleDelete(property.id)}
              />
            ))}
          </tbody>
        </table>
      </div>
    </section>
  );
};

export default Dashboard;
