import React, { useState } from 'react';
import { PaymentLinkForm } from './components/PaymentLinkForm';
import { PaymentLinkList } from './components/PaymentLinkList';
import { PaymentLink, PaymentStatus } from './types/PaymentLink';
import { CreditCard } from 'lucide-react';

const App: React.FC = () => {
  const [paymentLinks, setPaymentLinks] = useState<PaymentLink[]>([]);

  const handleAddPaymentLink = (newLink: PaymentLink) => {
    const linkWithId = { ...newLink, id: Date.now() };
    setPaymentLinks([...paymentLinks, linkWithId]);
  };

  const handleStatusUpdate = (id: number, newStatus: PaymentStatus) => {
    setPaymentLinks(paymentLinks.map(link => 
      link.id === id ? { ...link, status: newStatus } : link
    ));
  };

  return (
    <div className="min-h-screen bg-gray-100 py-10 px-4">
      <div className="max-w-4xl mx-auto">
        <header className="flex items-center justify-center mb-8">
          <CreditCard className="mr-4 text-blue-600" size={48} />
          <h1 className="text-4xl font-bold text-gray-800">
            Controle de Links de Pagamento
          </h1>
        </header>

        <div className="grid md:grid-cols-2 gap-8">
          <PaymentLinkForm onSubmit={handleAddPaymentLink} />
          <PaymentLinkList 
            links={paymentLinks} 
            onStatusUpdate={handleStatusUpdate} 
          />
        </div>
      </div>
    </div>
  );
};

export default App;
