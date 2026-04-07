import { useState } from "react";
import { Send, CheckCircle, Mail, Phone, MapPin } from "lucide-react";

export default function Contact() {
  const [form, setForm] = useState({ name: "", email: "", subject: "", message: "" });
  const [errors, setErrors] = useState<Record<string, string>>({});
  const [submitted, setSubmitted] = useState(false);

  const validate = () => {
    const e: Record<string, string> = {};
    if (!form.name.trim()) e.name = "Vui lòng nhập họ tên";
    if (!form.email.trim()) e.email = "Vui lòng nhập email";
    else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(form.email)) e.email = "Email không hợp lệ";
    if (!form.message.trim()) e.message = "Vui lòng nhập nội dung";
    return e;
  };

  const handleChange = (e: React.ChangeEvent<HTMLInputElement | HTMLTextAreaElement | HTMLSelectElement>) => {
    setForm((p) => ({ ...p, [e.target.name]: e.target.value }));
    if (errors[e.target.name]) setErrors((p) => ({ ...p, [e.target.name]: "" }));
  };

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    const errs = validate();
    if (Object.keys(errs).length) { setErrors(errs); return; }
    setSubmitted(true);
  };

  return (
    <div className="paper-texture" style={{ minHeight: "80vh" }}>
      <div className="page-hero">
        <div className="page-container">
          <p className="section-label">Kết Nối</p>
          <h1 style={{ fontFamily: "var(--font-display)", fontSize: "clamp(1.8rem,4vw,2.8rem)", color: "var(--color-ink)" }}>
            Liên Hệ Với Chúng Tôi
          </h1>
          <p style={{ fontFamily: "var(--font-body)", color: "var(--color-muted)", fontSize: "0.9rem", marginTop: "0.35rem" }}>
            Có câu hỏi, góp ý, hay muốn chia sẻ công thức? Chúng tôi luôn lắng nghe.
          </p>
        </div>
      </div>

      <div className="page-container" style={{ paddingTop: "2rem", paddingBottom: "3rem" }}>
        <div
          style={{
            display: "grid",
            gridTemplateColumns: "1fr 1.5fr",
            gap: "3rem",
            alignItems: "start",
          }}
          className="contact-grid"
        >
          {/* Contact info */}
          <div>
            <h2
              style={{
                fontFamily: "var(--font-display)",
                fontSize: "1.15rem",
                color: "var(--color-ink)",
                borderBottom: "2px solid var(--color-ink)",
                paddingBottom: "0.4rem",
                marginBottom: "1.25rem",
              }}
            >
              Thông Tin Liên Hệ
            </h2>

            {[
              { icon: <Mail size={16} />, label: "Email", value: "hello@vinha.vn" },
              { icon: <Phone size={16} />, label: "Điện Thoại", value: "+84 (0) 123 456 789" },
              { icon: <MapPin size={16} />, label: "Địa Chỉ", value: "123 Đường Ẩm Thực, Quận 1, TP. HCM" },
            ].map((item) => (
              <div
                key={item.label}
                style={{
                  display: "flex",
                  gap: "0.75rem",
                  alignItems: "flex-start",
                  marginBottom: "1rem",
                  paddingBottom: "1rem",
                  borderBottom: "1px dotted var(--color-rule)",
                }}
              >
                <span style={{ color: "var(--color-red)", marginTop: "0.1rem" }}>{item.icon}</span>
                <div>
                  <p className="field-label" style={{ marginBottom: "0.1rem" }}>{item.label}</p>
                  <p style={{ fontFamily: "var(--font-body)", fontSize: "0.88rem", color: "var(--color-ink-light)" }}>
                    {item.value}
                  </p>
                </div>
              </div>
            ))}

            {/* FAQ */}
            <div
              style={{
                background: "var(--color-card-bg)",
                border: "1px solid var(--color-gold-light)",
                padding: "1.1rem",
                marginTop: "1rem",
              }}
            >
              <h3
                style={{
                  fontFamily: "var(--font-display)",
                  fontSize: "0.9rem",
                  color: "var(--color-ink)",
                  marginBottom: "0.75rem",
                  borderBottom: "1px solid var(--color-rule)",
                  paddingBottom: "0.35rem",
                }}
              >
                Câu Hỏi Thường Gặp
              </h3>
              {[
                { q: "Công thức có miễn phí không?", a: "Tất cả đều miễn phí hoàn toàn." },
                { q: "Tôi có thể gửi công thức không?", a: "Có! Liên hệ qua email để chia sẻ." },
                { q: "Có công thức cho người mới không?", a: "Có, phân loại theo độ khó rõ ràng." },
              ].map((item) => (
                <div key={item.q} style={{ marginBottom: "0.65rem" }}>
                  <p style={{ fontFamily: "var(--font-caption)", fontSize: "0.75rem", fontWeight: 600, color: "var(--color-ink)" }}>
                    {item.q}
                  </p>
                  <p style={{ fontFamily: "var(--font-body)", fontSize: "0.78rem", color: "var(--color-muted)", marginTop: "0.1rem" }}>
                    {item.a}
                  </p>
                </div>
              ))}
            </div>
          </div>

          {/* Form */}
          <div>
            <h2
              style={{
                fontFamily: "var(--font-display)",
                fontSize: "1.15rem",
                color: "var(--color-ink)",
                borderBottom: "2px solid var(--color-ink)",
                paddingBottom: "0.4rem",
                marginBottom: "1.25rem",
              }}
            >
              Gửi Tin Nhắn
            </h2>

            {submitted ? (
              <div
                style={{
                  textAlign: "center",
                  padding: "3rem 1rem",
                  border: "1px solid var(--color-gold-light)",
                  background: "var(--color-card-bg)",
                }}
              >
                <CheckCircle size={36} color="var(--color-green)" style={{ margin: "0 auto 1rem" }} />
                <h3
                  style={{
                    fontFamily: "var(--font-display)",
                    fontSize: "1.2rem",
                    color: "var(--color-ink)",
                    marginBottom: "0.5rem",
                  }}
                >
                  Cảm Ơn Bạn!
                </h3>
                <p style={{ fontFamily: "var(--font-body)", color: "var(--color-muted)", fontSize: "0.88rem", marginBottom: "1.25rem" }}>
                  Chúng tôi đã nhận được tin nhắn và sẽ phản hồi sớm nhất có thể.
                </p>
                <button
                  className="btn-outline"
                  onClick={() => { setSubmitted(false); setForm({ name: "", email: "", subject: "", message: "" }); }}
                >
                  Gửi tin nhắn khác
                </button>
              </div>
            ) : (
              <form onSubmit={handleSubmit} noValidate style={{ display: "flex", flexDirection: "column", gap: "1rem" }}>
                <div style={{ display: "grid", gridTemplateColumns: "1fr 1fr", gap: "1rem" }} className="form-two-col">
                  <div>
                    <label className="field-label">Họ và Tên <span style={{ color: "var(--color-red)" }}>*</span></label>
                    <input className={`field-input${errors.name ? " error" : ""}`} name="name" value={form.name} onChange={handleChange} placeholder="Nguyễn Văn A" />
                    {errors.name && <p className="field-error">{errors.name}</p>}
                  </div>
                  <div>
                    <label className="field-label">Email <span style={{ color: "var(--color-red)" }}>*</span></label>
                    <input className={`field-input${errors.email ? " error" : ""}`} name="email" type="email" value={form.email} onChange={handleChange} placeholder="email@example.com" />
                    {errors.email && <p className="field-error">{errors.email}</p>}
                  </div>
                </div>

                <div>
                  <label className="field-label">Chủ Đề</label>
                  <select className="field-input" name="subject" value={form.subject} onChange={handleChange}>
                    <option value="">Chọn chủ đề...</option>
                    <option value="recipe">Chia sẻ công thức</option>
                    <option value="question">Câu hỏi nấu ăn</option>
                    <option value="feedback">Góp ý website</option>
                    <option value="other">Khác</option>
                  </select>
                </div>

                <div>
                  <label className="field-label">Nội Dung <span style={{ color: "var(--color-red)" }}>*</span></label>
                  <textarea
                    className={`field-input${errors.message ? " error" : ""}`}
                    name="message"
                    rows={6}
                    value={form.message}
                    onChange={handleChange}
                    placeholder="Nhập nội dung tin nhắn..."
                    style={{ resize: "vertical" }}
                  />
                  {errors.message && <p className="field-error">{errors.message}</p>}
                </div>

                <div>
                  <button type="submit" className="btn-primary">
                    <Send size={13} /> Gửi Tin Nhắn
                  </button>
                </div>
              </form>
            )}
          </div>
        </div>
      </div>

      <style>{`
        @media (max-width: 768px) {
          .contact-grid { grid-template-columns: 1fr !important; }
          .form-two-col { grid-template-columns: 1fr !important; }
        }
      `}</style>
    </div>
  );
}
