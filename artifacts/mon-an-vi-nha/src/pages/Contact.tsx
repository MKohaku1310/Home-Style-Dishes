import { useState } from "react";
import { Mail, Phone, MapPin, Send, CheckCircle } from "lucide-react";

export default function Contact() {
  const [submitted, setSubmitted] = useState(false);
  const [form, setForm] = useState({
    name: "",
    email: "",
    subject: "",
    message: "",
  });
  const [errors, setErrors] = useState<Record<string, string>>({});

  const validate = () => {
    const newErrors: Record<string, string> = {};
    if (!form.name.trim()) newErrors.name = "Vui lòng nhập họ tên";
    if (!form.email.trim()) {
      newErrors.email = "Vui lòng nhập email";
    } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(form.email)) {
      newErrors.email = "Email không hợp lệ";
    }
    if (!form.message.trim()) newErrors.message = "Vui lòng nhập nội dung";
    return newErrors;
  };

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    const newErrors = validate();
    if (Object.keys(newErrors).length > 0) {
      setErrors(newErrors);
      return;
    }
    setErrors({});
    setSubmitted(true);
  };

  const handleChange = (
    e: React.ChangeEvent<HTMLInputElement | HTMLTextAreaElement | HTMLSelectElement>
  ) => {
    setForm((prev) => ({ ...prev, [e.target.name]: e.target.value }));
    if (errors[e.target.name]) {
      setErrors((prev) => ({ ...prev, [e.target.name]: "" }));
    }
  };

  const contactInfo = [
    {
      icon: <Mail className="w-5 h-5" />,
      label: "Email",
      value: "hello@vinha.vn",
      href: "mailto:hello@vinha.vn",
    },
    {
      icon: <Phone className="w-5 h-5" />,
      label: "Điện Thoại",
      value: "+84 (0) 123 456 789",
      href: "tel:+840123456789",
    },
    {
      icon: <MapPin className="w-5 h-5" />,
      label: "Địa Chỉ",
      value: "123 Đường Ẩm Thực, Quận 1, TP. HCM",
      href: "#",
    },
  ];

  return (
    <div className="min-h-screen pt-20 pb-16">
      {/* Header */}
      <div className="bg-primary/5 border-b border-border py-10">
        <div className="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
          <p className="text-primary text-sm font-semibold uppercase tracking-wider mb-1">
            Kết Nối
          </p>
          <h1 className="text-3xl sm:text-4xl font-bold text-foreground">
            Liên Hệ Với Chúng Tôi
          </h1>
          <p className="text-muted-foreground mt-2 max-w-lg">
            Bạn có câu hỏi, góp ý hay muốn chia sẻ công thức? Hãy nhắn gửi cho chúng tôi.
          </p>
        </div>
      </div>

      <div className="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div className="grid lg:grid-cols-5 gap-10">
          {/* Contact Info */}
          <div className="lg:col-span-2">
            <h2 className="text-xl font-bold text-foreground mb-6">Thông Tin Liên Hệ</h2>
            <div className="space-y-4 mb-8">
              {contactInfo.map((info) => (
                <a
                  key={info.label}
                  href={info.href}
                  className="flex items-start gap-4 p-4 bg-card border border-border rounded-xl hover:border-primary/40 transition-all group"
                >
                  <div className="w-10 h-10 bg-primary/10 rounded-xl flex items-center justify-center text-primary flex-shrink-0 group-hover:bg-primary group-hover:text-primary-foreground transition-all">
                    {info.icon}
                  </div>
                  <div>
                    <div className="text-sm text-muted-foreground">{info.label}</div>
                    <div className="text-foreground font-medium">{info.value}</div>
                  </div>
                </a>
              ))}
            </div>

            {/* FAQ */}
            <div className="bg-card border border-border rounded-2xl p-5">
              <h3 className="font-semibold text-foreground mb-4">Câu Hỏi Thường Gặp</h3>
              <div className="space-y-3">
                {[
                  {
                    q: "Công thức có miễn phí không?",
                    a: "Tất cả công thức đều miễn phí hoàn toàn.",
                  },
                  {
                    q: "Tôi có thể gửi công thức của mình không?",
                    a: "Có! Liên hệ chúng tôi qua email để chia sẻ.",
                  },
                  {
                    q: "Công thức có phù hợp người mới không?",
                    a: "Có, chúng tôi phân loại theo độ khó rõ ràng.",
                  },
                ].map((item) => (
                  <div key={item.q}>
                    <p className="text-sm font-medium text-foreground">{item.q}</p>
                    <p className="text-sm text-muted-foreground mt-0.5">{item.a}</p>
                  </div>
                ))}
              </div>
            </div>
          </div>

          {/* Contact Form */}
          <div className="lg:col-span-3">
            {submitted ? (
              <div className="flex flex-col items-center justify-center h-full py-16 text-center">
                <div className="w-16 h-16 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center mb-4">
                  <CheckCircle className="w-8 h-8 text-green-600 dark:text-green-400" />
                </div>
                <h2 className="text-2xl font-bold text-foreground mb-2">
                  Cảm ơn bạn!
                </h2>
                <p className="text-muted-foreground mb-6 max-w-md">
                  Chúng tôi đã nhận được tin nhắn của bạn. Chúng tôi sẽ phản hồi
                  sớm nhất có thể, thường trong vòng 24 giờ.
                </p>
                <button
                  onClick={() => {
                    setSubmitted(false);
                    setForm({ name: "", email: "", subject: "", message: "" });
                  }}
                  className="bg-primary text-primary-foreground px-5 py-2.5 rounded-xl font-medium hover:bg-primary/90 transition-colors"
                >
                  Gửi tin nhắn khác
                </button>
              </div>
            ) : (
              <form onSubmit={handleSubmit} noValidate className="space-y-5">
                <h2 className="text-xl font-bold text-foreground mb-6">Gửi Tin Nhắn</h2>

                <div className="grid sm:grid-cols-2 gap-5">
                  <div>
                    <label className="block text-sm font-medium text-foreground mb-1.5">
                      Họ và Tên <span className="text-destructive">*</span>
                    </label>
                    <input
                      type="text"
                      name="name"
                      value={form.name}
                      onChange={handleChange}
                      placeholder="Nguyễn Văn A"
                      className={`w-full px-4 py-2.5 border rounded-xl bg-background text-foreground placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent text-sm transition-colors ${
                        errors.name ? "border-destructive" : "border-border"
                      }`}
                    />
                    {errors.name && (
                      <p className="mt-1 text-xs text-destructive">{errors.name}</p>
                    )}
                  </div>
                  <div>
                    <label className="block text-sm font-medium text-foreground mb-1.5">
                      Email <span className="text-destructive">*</span>
                    </label>
                    <input
                      type="email"
                      name="email"
                      value={form.email}
                      onChange={handleChange}
                      placeholder="email@example.com"
                      className={`w-full px-4 py-2.5 border rounded-xl bg-background text-foreground placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent text-sm transition-colors ${
                        errors.email ? "border-destructive" : "border-border"
                      }`}
                    />
                    {errors.email && (
                      <p className="mt-1 text-xs text-destructive">{errors.email}</p>
                    )}
                  </div>
                </div>

                <div>
                  <label className="block text-sm font-medium text-foreground mb-1.5">
                    Chủ Đề
                  </label>
                  <select
                    name="subject"
                    value={form.subject}
                    onChange={handleChange}
                    className="w-full px-4 py-2.5 border border-border rounded-xl bg-background text-foreground focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent text-sm"
                  >
                    <option value="">Chọn chủ đề...</option>
                    <option value="recipe">Chia sẻ công thức</option>
                    <option value="question">Câu hỏi về nấu ăn</option>
                    <option value="feedback">Góp ý website</option>
                    <option value="other">Khác</option>
                  </select>
                </div>

                <div>
                  <label className="block text-sm font-medium text-foreground mb-1.5">
                    Nội Dung <span className="text-destructive">*</span>
                  </label>
                  <textarea
                    name="message"
                    value={form.message}
                    onChange={handleChange}
                    rows={6}
                    placeholder="Nhập nội dung tin nhắn của bạn..."
                    className={`w-full px-4 py-2.5 border rounded-xl bg-background text-foreground placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent text-sm resize-none transition-colors ${
                      errors.message ? "border-destructive" : "border-border"
                    }`}
                  />
                  {errors.message && (
                    <p className="mt-1 text-xs text-destructive">{errors.message}</p>
                  )}
                </div>

                <button
                  type="submit"
                  className="w-full sm:w-auto flex items-center justify-center gap-2 bg-primary hover:bg-primary/90 text-primary-foreground px-8 py-3 rounded-xl font-semibold transition-all hover:scale-105 shadow-sm"
                >
                  <Send className="w-4 h-4" />
                  Gửi Tin Nhắn
                </button>
              </form>
            )}
          </div>
        </div>
      </div>
    </div>
  );
}
