export default function About() {
  return (
    <div className="paper-texture" style={{ minHeight: "80vh" }}>
      <div className="page-hero">
        <div className="page-container">
          <p className="section-label">Về Chúng Tôi</p>
          <h1
            style={{
              fontFamily: "var(--font-display)",
              fontSize: "clamp(2rem, 5vw, 3.2rem)",
              color: "var(--color-ink)",
              marginBottom: "0.25rem",
            }}
          >
            Hương Vị Bếp Nhà
          </h1>
          <p
            style={{
              fontFamily: "var(--font-body)",
              fontStyle: "italic",
              color: "var(--color-muted)",
              fontSize: "0.95rem",
            }}
          >
            Câu chuyện về những công thức nấu ăn được truyền từ đời này sang đời khác
          </p>
        </div>
      </div>

      <div className="page-container" style={{ paddingTop: "2.5rem", paddingBottom: "3rem" }}>
        {/* Headline */}
        <h2
          style={{
            fontFamily: "var(--font-display)",
            fontSize: "clamp(1.4rem, 3vw, 2rem)",
            color: "var(--color-ink)",
            textAlign: "center",
            marginBottom: "0.5rem",
          }}
        >
          Tại Sao Lại Là "Vị Nhà"?
        </h2>
        <p
          style={{
            textAlign: "center",
            fontFamily: "var(--font-caption)",
            fontStyle: "italic",
            color: "var(--color-muted)",
            fontSize: "0.82rem",
            marginBottom: "2rem",
          }}
        >
          — Bởi vì không nơi nào ngon hơn bếp nhà mình —
        </p>

        {/* Two-column article body with drop cap */}
        <div
          className="drop-cap newspaper-columns"
          style={{
            fontFamily: "var(--font-body)",
            fontSize: "0.97rem",
            lineHeight: 1.85,
            color: "var(--color-ink-light)",
            marginBottom: "2.5rem",
          }}
        >
          <p style={{ marginBottom: "1rem" }}>
            Món Ăn Vị Nhà ra đời từ một buổi chiều mưa, khi người sáng lập ngồi nhớ lại
            mâm cơm mẹ nấu — nồi canh chua bốc khói, chén cơm trắng thơm và tiếng muỗng
            đũa lách cách. Không có nhà hàng nào có thể tái hiện được cái hương vị đó. Chỉ
            có ký ức và đôi tay quen thuộc mới làm được.
          </p>
          <p style={{ marginBottom: "1rem" }}>
            Đó là lý do chúng tôi tạo ra trang web này — không phải để dạy bạn nấu
            ăn theo kiểu chuyên nghiệp, mà để giúp bạn nhớ lại, học lại, và tái hiện
            những hương vị bếp nhà mà đôi khi chúng ta bỗng nhận ra mình đã đánh mất từ
            lúc nào không hay.
          </p>
          <p style={{ marginBottom: "1rem" }}>
            Mỗi công thức ở đây đều mang theo một câu chuyện — câu chuyện về người nấu,
            về ký ức bữa cơm, về vùng đất nơi món ăn được sinh ra. Chúng tôi tin rằng ẩm
            thực không chỉ là dinh dưỡng — đó là ngôn ngữ của tình thương, là sợi dây gắn
            kết con người với nhau qua từng thế hệ.
          </p>
          <p>
            Dù bạn là người lần đầu cầm muỗng hay đã có hàng chục năm kinh nghiệm bên bếp lửa,
            chúng tôi hy vọng mỗi công thức ở đây sẽ đưa bạn trở về một góc bếp ấm áp nào đó
            trong ký ức — nơi mùi thức ăn còn quyện với tiếng cười nói của gia đình.
          </p>
        </div>

        <div className="ornament-divider">◆ Sứ Mệnh ◆</div>

        {/* Mission cards */}
        <div
          style={{
            display: "grid",
            gridTemplateColumns: "repeat(auto-fit, minmax(220px, 1fr))",
            gap: "1.25rem",
            marginBottom: "3rem",
          }}
        >
          {[
            {
              icon: "📖",
              title: "Lưu Giữ Công Thức",
              body: "Ghi chép tỉ mỉ những công thức nấu ăn gia đình truyền thống trước khi chúng bị lãng quên theo thời gian.",
            },
            {
              icon: "🍲",
              title: "Dành Cho Mọi Người",
              body: "Từ người mới học nấu đến người muốn tái hiện hương vị tuổi thơ — công thức rõ ràng, dễ làm theo.",
            },
            {
              icon: "❤️",
              title: "Ẩm Thực Là Tình Thương",
              body: "Chúng tôi tin mỗi bữa cơm nấu từ trái tim đều mang theo một phần linh hồn của người nấu.",
            },
            {
              icon: "🌿",
              title: "Nguyên Liệu Sạch",
              body: "Ưu tiên nguyên liệu tươi, sạch, dễ tìm để bạn có thể nấu bất cứ lúc nào.",
            },
          ].map((item) => (
            <div
              key={item.title}
              style={{
                background: "var(--color-card-bg)",
                border: "1px solid var(--color-gold-light)",
                padding: "1.25rem",
                borderRadius: "var(--radius-xs)",
              }}
            >
              <div style={{ fontSize: "1.8rem", marginBottom: "0.6rem" }}>{item.icon}</div>
              <h3
                style={{
                  fontFamily: "var(--font-display)",
                  fontSize: "0.95rem",
                  color: "var(--color-ink)",
                  marginBottom: "0.4rem",
                }}
              >
                {item.title}
              </h3>
              <p style={{ fontFamily: "var(--font-body)", fontSize: "0.82rem", color: "var(--color-muted)", lineHeight: 1.7 }}>
                {item.body}
              </p>
            </div>
          ))}
        </div>

        {/* Team */}
        <div className="ornament-divider">◆ Đội Ngũ ◆</div>
        <div
          style={{
            display: "grid",
            gridTemplateColumns: "repeat(auto-fit, minmax(220px, 1fr))",
            gap: "1.5rem",
            marginBottom: "2rem",
          }}
        >
          {[
            {
              name: "Nguyễn Thị Hoa",
              role: "Đầu Bếp Chính",
              bio: "20 năm kinh nghiệm nấu ăn gia đình miền Nam. Người đã ghi chép lại hầu hết các công thức trên website này.",
              avatar: "https://images.unsplash.com/photo-1607631568010-a87245c0daf8?w=150&q=80",
            },
            {
              name: "Trần Văn Nam",
              role: "Chuyên Gia Ẩm Thực",
              bio: "Nghiên cứu ẩm thực truyền thống Việt Nam ba miền. Tác giả của hai cuốn sách về văn hoá ẩm thực.",
              avatar: "https://images.unsplash.com/photo-1622021142947-da7dedc7c39a?w=150&q=80",
            },
            {
              name: "Lê Thị Mai",
              role: "Biên Soạn Nội Dung",
              bio: "Đam mê viết và ẩm thực. Chuyên sưu tầm các công thức từ khắp ba miền Bắc Trung Nam.",
              avatar: "https://images.unsplash.com/photo-1594744803329-e58b31de8bf5?w=150&q=80",
            },
          ].map((m) => (
            <div key={m.name} style={{ textAlign: "center" }}>
              <img
                src={m.avatar}
                alt={m.name}
                style={{
                  width: "80px",
                  height: "80px",
                  borderRadius: "50%",
                  objectFit: "cover",
                  margin: "0 auto 0.75rem",
                  filter: "sepia(20%)",
                  border: "2px solid var(--color-gold-light)",
                }}
              />
              <h4 style={{ fontFamily: "var(--font-display)", fontSize: "1rem", color: "var(--color-ink)" }}>
                {m.name}
              </h4>
              <p
                style={{
                  fontFamily: "var(--font-caption)",
                  fontSize: "0.72rem",
                  color: "var(--color-red)",
                  letterSpacing: "0.08em",
                  textTransform: "uppercase",
                  marginBottom: "0.4rem",
                }}
              >
                {m.role}
              </p>
              <p style={{ fontFamily: "var(--font-body)", fontSize: "0.82rem", color: "var(--color-muted)", lineHeight: 1.65 }}>
                {m.bio}
              </p>
            </div>
          ))}
        </div>
      </div>
    </div>
  );
}
