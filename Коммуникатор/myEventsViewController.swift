//
//  myEventsViewController.swift
//  Коммуникатор
//
//  Created by Наталья Лозинская on 13.01.2021.
//

import UIKit

class myEventsViewController: UIViewController {

    //массив данных
    var my_events = [["0","День кофе: пьем и не спим", "Собираемся, наливаем, выпиваем и уходим. Перед уходом платим, наличными конечно.", "7 января в 19:00", "До 60 человек","img_1"], ["1","День еды: собираемся, объедаемся и уходим", "Все как обычно, вы можете наесться за 10 минут – тогда ваш ужин обойдется вам в 10₽. Вам выгодно, а нам нет.", "7 января в 19:00", "До 60 человек","img_2"]]
    let idCell = "MailCell"
    @IBOutlet weak var myEventsTable: UITableView!
    override func viewDidLoad() {
        super.viewDidLoad()
        myEventsTable.dataSource = self
        myEventsTable.delegate = self

        myEventsTable.register(UINib(nibName: "MainTableViewCell", bundle: nil ), forCellReuseIdentifier: idCell)
    }
    

    /*
    // MARK: - Navigation

    // In a storyboard-based application, you will often want to do a little preparation before navigation
    override func prepare(for segue: UIStoryboardSegue, sender: Any?) {
        // Get the new view controller using segue.destination.
        // Pass the selected object to the new view controller.
    }
    */

}

extension myEventsViewController: UITableViewDataSource, UITableViewDelegate{
    
    //количество создаваемых клеток
    func tableView(_ tableView: UITableView, numberOfRowsInSection section: Int) -> Int {
        return self.my_events.count
    }
    //создание клетки
    func tableView(_ tableView: UITableView, cellForRowAt indexPath: IndexPath) -> UITableViewCell {
        let cell = myEventsTable.dequeueReusableCell(withIdentifier: idCell) as! MainTableViewCell
        cell.titleEvent.text = self.my_events[indexPath.row][1]
        cell.descriptionEvent.text = self.my_events[indexPath.row][2]
        cell.imgEvent.image = UIImage(named: "eventImg")
        cell.timeEvent.text = self.my_events[indexPath.row][3]
        cell.countPersonsEvent.text = self.my_events[indexPath.row][4]
        
        return cell
    }
    //высота клетки
    func tableView(_ tableView: UITableView, heightForRowAt indexPath: IndexPath) -> CGFloat {
        return 313.0
    }
    //вывод индекса выбранной строки
    func tableView(_ tableView: UITableView, didSelectRowAt indexPath: IndexPath) {
        print("\(indexPath.row)")
        performSegue(withIdentifier: "showdetail", sender: self)
    }
    
    override func prepare(for segue: UIStoryboardSegue, sender: Any?) {
        if let destination = segue.destination as? DetailVC {
            destination.chooseCell = my_events[(myEventsTable.indexPathForSelectedRow?.row)!]
        }
    }
}
