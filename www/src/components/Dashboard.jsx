import React, { useEffect, useState } from 'react';

const StatBox = ({ number, label }) => (
  <div className="bg-gradient-to-br from-primary to-secondary text-white p-6 rounded-lg text-center">
    <div className="text-4xl font-bold mb-2">{number}</div>
    <div className="text-lg opacity-90">{label}</div>
  </div>
);

const PropertyRow = ({ name, address, tenants, status, onView }) => (
  <tr className="border-b hover:bg-gray-50">
    <td className="px-4 py-3">{name}</td>
    <td className="px-4 py-3">{address}</td>
    <td className="px-4 py-3">{tenants}</td>
    <td className="px-4 py-3">
      <span className={`font-semibold ${status === 'Active' ? 'text-green-600' : 'text-yellow-600'}`}>
        {status}
      </span>
    </td>
    <td className="px-4 py-3">
      <button onClick={onView} className="px-3 py-1 bg-primary text-white rounded text-sm hover:opacity-80 transition">View</button>
    </td>
  </tr>
);

const formatRevenue = (amount) => {
  if (amount >= 1000) return `$${(amount / 1000).toFixed(1)}K`;
  return `$${Number(amount).toFixed(0)}`;
};

const Dashboard = ({ token }) => {
  const [stats, setStats] = useState(null);
  const [properties, setProperties] = useState([]);
  const [serverStatus, setServerStatus] = useState('Loading…');

  useEffect(() => {
    if (!token) {
      setServerStatus('Not authenticated');
      setStats(null);
      setProperties([]);
      return;
    }

    const headers = { Authorization: `Bearer ${token}` };

    fetch('/api/dashboard', { headers })
      .then((r) => r.json())
      .then((data) => {
        setServerStatus(data.status === 'ok' ? 'Connected' : data.status);
        if (data.stats) setStats(data.stats);
      })
      .catch(() => setServerStatus('Offline'));

    fetch('/api/properties', { headers })
      .then((r) => r.json())
      .then((data) => {
        if (data.properties) setProperties(data.properties);
      })
      .catch(() => {});
  }, [token]);

  return (
    <section id="properties" className="bg-white rounded-lg p-8 shadow-lg">
      <div className="flex flex-col md:flex-row md:items-center md:justify-between mb-8 gap-4">
        <div>
          <h2 className="text-4xl font-bold text-gray-800">Dashboard Overview</h2>
          <p className="text-gray-600 mt-2">
            Server: <span className="font-semibold">{serverStatus}</span>
            {!token && (
              <span className="ml-2 text-sm text-gray-400">(log in to see live data)</span>
            )}
          </p>
        </div>
      </div>

      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <StatBox number={stats ? stats.total_properties : '—'} label="Total Properties" />
        <StatBox number={stats ? stats.active_tenants : '—'} label="Active Tenants" />
        <StatBox number={stats ? formatRevenue(stats.monthly_revenue) : '—'} label="Monthly Revenue" />
        <StatBox number={stats ? stats.open_maintenance : '—'} label="Maintenance Tasks" />
      </div>

      <h3 className="text-2xl font-bold text-gray-800 mb-4">Recent Properties</h3>
      {properties.length === 0 ? (
        <p className="text-gray-500">{token ? 'No properties found.' : 'Log in to view properties.'}</p>
      ) : (
        <div className="overflow-x-auto">
          <table className="w-full border-collapse">
            <thead className="bg-gray-100">
              <tr>
                <th className="px-4 py-3 text-left font-semibold text-gray-800">Property Name</th>
                <th className="px-4 py-3 text-left font-semibold text-gray-800">Address</th>
                <th className="px-4 py-3 text-left font-semibold text-gray-800">Active Tenants</th>
                <th className="px-4 py-3 text-left font-semibold text-gray-800">Type</th>
                <th className="px-4 py-3 text-left font-semibold text-gray-800">Actions</th>
              </tr>
            </thead>
            <tbody>
              {properties.map((p) => (
                <PropertyRow
                  key={p.id}
                  name={p.name}
                  address={`${p.address}${p.city ? ', ' + p.city : ''}`}
                  tenants={p.tenantCount ?? 0}
                  status={p.propertyType}
                  onView={() => alert(`Viewing property ${p.id}: ${p.name}`)}
                />
              ))}
            </tbody>
          </table>
        </div>
      )}
    </section>
  );
};

export default Dashboard;
